<?php

namespace App\Containers\AppSection\Game\UI\API\Transformers;

use App\Containers\AppSection\Game\Models\UserWorld;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Transformers\Transformer;
use JetBrains\PhpStorm\ArrayShape;

class UserWorldTransformer extends Transformer
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
    public function transform(UserWorld $userWorld): array
    {
        return [
            'id' => $userWorld->id,
            'status' => $userWorld->status,
            'form_settings' => $userWorld->form_settings,
            'created_at' => $userWorld->created_at,
            'readable_created_at' => $userWorld->created_at->diffForHumans(),
            'author' => [
                'id' => $userWorld->author->getHashedKey(),
                'name' => $userWorld->author->name,
            ],
            'players' => $userWorld->players->map(
                static fn(User $player): array => [
                    'id' => $player->getHashedKey(),
                    'name' => $player->name,
                ],
            ),
        ];
    }
}
