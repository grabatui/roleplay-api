<?php

namespace App\Containers\AppSection\User\Actions\Entity;

class UserSettingDto
{
    public function __construct(
        private string $code,
        private mixed $value
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}