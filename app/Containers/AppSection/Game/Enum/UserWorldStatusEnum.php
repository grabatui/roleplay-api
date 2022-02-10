<?php

namespace App\Containers\AppSection\Game\Enum;

class UserWorldStatusEnum
{
    public const NEW = 'new';
    public const IN_PROGRESS = 'in_progress';
    public const DELETED = 'deleted';

    public const ALL = [
        self::NEW,
        self::IN_PROGRESS,
        self::DELETED,
    ];
}