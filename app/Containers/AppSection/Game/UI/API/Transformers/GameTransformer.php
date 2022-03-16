<?php

namespace App\Containers\AppSection\Game\UI\API\Transformers;

use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Transformers\Transformer;
use JetBrains\PhpStorm\ArrayShape;

class GameTransformer extends Transformer
{
    #[ArrayShape([
        'id' => "int",
        'status' => "string",
        'form_settings' => "array",
        'created_at' => "string",
        'readable_created_at' => "string",
        'author' => "array",
        'players' => "array"
    ])]
    public function transform(Game $game): array
    {
        return [
            'id' => $game->id,
            'status' => $game->status,
            'form_settings' => $game->form_settings,
            'created_at' => $game->created_at,
            'readable_created_at' => $game->created_at->diffForHumans(),
            'author' => [
                'id' => $game->author->getHashedKey(),
                'name' => $game->author->name,
            ],
            'players' => $game->players->map(
                static fn(User $player): array => [
                    'id' => $player->getHashedKey(),
                    'name' => $player->name,
                ],
            ),
        ];
    }
}
