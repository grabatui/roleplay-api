<?php

namespace App\Containers\AppSection\Game\Rules;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use Illuminate\Contracts\Validation\Rule;

class GameSettingsRule implements Rule
{
    private array $requiredEmptySettings = [];

    public function __construct(
        private ?WorldAdapterInterface $worldAdapter
    ) {
    }

    public function passes($attribute, $value): bool
    {
        $settingCodesFromRequest = array_map(
            static fn(array $setting): string => $setting['code'] ?? '',
            $value
        );

        $this->requiredEmptySettings = [];
        foreach ($this->worldAdapter->getSettings() as $setting) {
            if ($setting->isRequired() && !in_array($setting->getCode(), $settingCodesFromRequest)) {
                $this->requiredEmptySettings[] = $setting->getCode();
            }
        }

        return empty($this->requiredEmptySettings);
    }

    public function message(): string
    {
        return __('appSection@game::error.game_validation.required_not_filled', [
            'codes' => implode(', ', $this->requiredEmptySettings),
        ]);
    }
}
