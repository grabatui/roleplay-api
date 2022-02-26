<?php

namespace App\Containers\AppSection\Game\Exceptions;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Setting;
use App\Containers\AppSection\Game\Enum\UserWorldValidationTypeEnum;
use App\Ship\Exceptions\ValidationFailedException;
use Exception as BaseException;

class UserWorldValidationFailedException extends ValidationFailedException
{
    public function __construct(
        private string $settingCode,
        private UserWorldValidationTypeEnum $type,
        ?string $message = null,
        ?int $code = null,
        ?BaseException $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getSettingCode(): string
    {
        return $this->settingCode;
    }

    public function getTranslatedError(): string
    {
        return __('appSection@game::error.user_world_validation.' . $this->type->name, [
            'code' => $this->settingCode,
        ]);
    }
}
