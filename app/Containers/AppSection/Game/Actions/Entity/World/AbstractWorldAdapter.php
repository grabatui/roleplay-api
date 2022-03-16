<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Setting;
use App\Containers\AppSection\Game\Enum\GameValidationTypeEnum;
use App\Containers\AppSection\Game\Exceptions\GameValidationFailedException;

abstract class AbstractWorldAdapter implements WorldAdapterInterface
{
    public function getSettingByCode(string $code): ?Setting
    {
        foreach ($this->getSettings() as $setting) {
            if ($setting->getCode() === $code) {
                return $setting;
            }
        }

        return null;
    }

    public function validateSetting(Setting $setting, mixed $value): void
    {
        if ($setting->isRequired() && !$value) {
            throw new GameValidationFailedException(
                $setting->getCode(),
                GameValidationTypeEnum::required
            );
        }
    }

    public function hasRequiredSettings(): bool
    {
        foreach ($this->getSettings() as $setting) {
            if ($setting->isRequired()) {
                return true;
            }
        }

        return false;
    }
}
