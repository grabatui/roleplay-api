<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field\Type;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Setting;
use JetBrains\PhpStorm\ArrayShape;

class DndWorldAdapter extends AbstractWorldAdapter
{
    public const TITLE = 'title';
    public const MAX_PLAYERS_COUNT = 'maxPlayersCount';

    public static function getCode(): string
    {
        return 'dnd';
    }

    #[ArrayShape([
        self::TITLE => "\App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Setting",
        self::MAX_PLAYERS_COUNT => "\App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Setting"
    ])]
    public function getSettings(): array
    {
        return [
            static::TITLE => Setting::make(static::TITLE, new Type(Type::STRING))
                ->setIsRequired(true),
            static::MAX_PLAYERS_COUNT => Setting::make(static::MAX_PLAYERS_COUNT, new Type(Type::INTEGER))
                ->setIsHintExists(true),
        ];
    }
}
