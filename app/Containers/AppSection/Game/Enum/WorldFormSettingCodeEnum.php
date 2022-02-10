<?php

namespace App\Containers\AppSection\Game\Enum;

class WorldFormSettingCodeEnum
{
    public const TITLE = 'title';
    public const MAX_PLAYERS_COUNT = 'maxPlayersCount';

    public const ALL_DND = [
        self::TITLE,
        self::MAX_PLAYERS_COUNT,
    ];
}
