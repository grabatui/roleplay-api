<?php

namespace App\Containers\AppSection\User\Tasks\Entity;

class UserSettingDto
{
    private string $code;
    private mixed $value;

    public function __construct(
        string $code,
        mixed $value
    ) {
        $this->code = $code;
        $this->value = $value;
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