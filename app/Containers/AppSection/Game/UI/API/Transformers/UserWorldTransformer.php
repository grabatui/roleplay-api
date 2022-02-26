<?php

namespace App\Containers\AppSection\Game\UI\API\Transformers;

use App\Containers\AppSection\Game\Models\UserWorld;
use App\Ship\Parents\Transformers\Transformer;
use JetBrains\PhpStorm\ArrayShape;

class UserWorldTransformer extends Transformer
{
    #[ArrayShape([
        'id' => "int",
        'status' => "string",
        'form_settings' => "array",
        'created_at' => "object",
        'readable_created_at' => "string",
        'author_id' => "mixed",
        'author_name' => "string"
    ])]
    public function transform(UserWorld $userWorld): array
    {
        return [
            'id' => $userWorld->id,
            'status' => $userWorld->status,
            'form_settings' => $userWorld->form_settings,
            'created_at' => $userWorld->created_at,
            'readable_created_at' => $userWorld->created_at->diffForHumans(),
            'author_id' => $userWorld->author->getHashedKey(),
            'author_name' => $userWorld->author->name,
            // TODO: Number of players
        ];
    }
}
