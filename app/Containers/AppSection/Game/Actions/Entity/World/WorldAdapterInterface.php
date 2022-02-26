<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Setting;
use App\Containers\AppSection\Game\Exceptions\UserWorldValidationFailedException;

interface WorldAdapterInterface
{
    public static function getCode(): string;

    /**
     * @return array<string, Setting>
     */
    public function getSettings(): array;

    public function getSettingByCode(string $code): ?Setting;

    /**
     * @param Setting $setting
     * @param mixed $value
     * @return void
     * @throws UserWorldValidationFailedException
     */
    public function validateSetting(Setting $setting, mixed $value): void;

    public function hasRequiredSettings(): bool;
}
