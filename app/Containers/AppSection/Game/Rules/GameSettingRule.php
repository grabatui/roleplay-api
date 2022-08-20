<?php

namespace App\Containers\AppSection\Game\Rules;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use App\Containers\AppSection\Game\Exceptions\GameValidationFailedException;
use Illuminate\Contracts\Validation\Rule;

class GameSettingRule implements Rule
{
    private string $errorMessage;
    private ?string $notExistsSetting = null;

    public function __construct(
        private ?WorldAdapterInterface $worldAdapter
    ) {
        $this->errorMessage =  __('appSection@game::error.game_validation.unknown');
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (!$this->worldAdapter) {
            return true;
        }

        $this->notExistsSetting = null;

        $code = $value['code'] ?? null;

        if (!$code) {
            return true;
        }

        $setting = $this->worldAdapter->getFormFieldByCode($code);

        if (!$setting) {
            $this->notExistsSetting = $code;

            return false;
        }

        try {
            $this->worldAdapter->validateField($setting, $value['value'] ?? null);
        } catch (GameValidationFailedException $exception) {
            $this->errorMessage = $exception->getTranslatedError();

            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->notExistsSetting
            ? __('appSection@game::error.game_validation.not_exists', [
                'code' => $this->notExistsSetting,
            ])
            : $this->errorMessage;
    }
}
