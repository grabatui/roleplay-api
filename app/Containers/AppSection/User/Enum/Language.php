<?php

namespace App\Containers\AppSection\User\Enum;

class Language
{
    public const RU = 'ru';
    public const EN = 'en';

    public const DEFAULT = self::RU;

    public const ALL = [
        self::RU,
        self::EN,
    ];
}
