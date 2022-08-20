<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field\TypeEnum;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\ListField;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Option;

class DndWorldAdapter extends AbstractWorldAdapter
{
    public const FORM_TITLE = 'title';
    public const FORM_MAX_PLAYERS_COUNT = 'maxPlayersCount';

    public const CHARACTER_NAME = 'name';
    public const CHARACTER_RACE = 'race';
    public const CHARACTER_CLASS = 'class';

    public static function getCode(): string
    {
        return 'dnd';
    }

    public function getFormFields(): array
    {
        return [
            static::FORM_TITLE => Field::make(static::FORM_TITLE, TypeEnum::STRING)
                ->setIsRequired(true),
            static::FORM_MAX_PLAYERS_COUNT => Field::make(static::FORM_MAX_PLAYERS_COUNT, TypeEnum::INTEGER)
                ->setIsHintExists(true),
        ];
    }

    public function getCharacterFields(): array
    {
        return [
            static::CHARACTER_NAME => Field::make(static::CHARACTER_NAME, TypeEnum::STRING)
                ->setIsRequired(true),
            static::CHARACTER_RACE => ListField::make(static::CHARACTER_RACE, static::getRaces())
                ->setIsRequired(true),
            static::CHARACTER_CLASS => ListField::make(static::CHARACTER_CLASS, static::getClasses())
                ->setIsRequired(true),
        ];
    }

    /**
     * @return Option[]
     */
    public static function getRaces(): array
    {

    }

    /**
     * @return Option[]
     */
    public static function getClasses(): array
    {

    }
}
