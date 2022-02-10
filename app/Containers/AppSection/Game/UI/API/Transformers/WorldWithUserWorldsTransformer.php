<?php

namespace App\Containers\AppSection\Game\UI\API\Transformers;

use App\Containers\AppSection\Game\Actions\Entity\WorldWithUserWorlds;
use App\Containers\AppSection\Game\Models\UserWorld;
use App\Ship\Parents\Transformers\Transformer;
use JetBrains\PhpStorm\ArrayShape;

class WorldWithUserWorldsTransformer extends Transformer
{
    #[ArrayShape([
        'code' => "string",
        'title' => "mixed",
        'form_settings' => "array",
        'user_worlds' => "array[]"
    ])]
    public function transform(WorldWithUserWorlds $worldWithUserWorlds): array
    {
        $world = $worldWithUserWorlds->getWorld();

        return [
            'code' => $world->code,
            'title' => __('appSection@game::world.names.' . $world->code),
            'form_settings' => array_map(
                fn(array $formSetting): array => $this->transformFormSetting($world->code, $formSetting),
                $world->form_settings
            ),
            'user_worlds' => $worldWithUserWorlds->getUserWorlds()->map(
                fn(UserWorld $userWorld): array => $this->transformUserWorld($userWorld)
            ),
        ];
    }

    private function transformFormSetting(string $worldCode, array $formSetting): array
    {
        if ($formSetting['hintExists'] ?? false) {
            $formSetting['hint'] = __(sprintf(
                'appSection@game::world.form_settings.hints.%s.%s',
                $worldCode,
                $formSetting['code']
            ));
        }

        return array_merge(
            $formSetting,
            [
                'title' => __(sprintf(
                    'appSection@game::world.form_settings.titles.%s.%s',
                    $worldCode,
                    $formSetting['code']
                )),
            ]
        );
    }

    #[ArrayShape([
        'id' => "int",
        'status' => "string",
        'form_settings' => "array",
        'created_at' => "object",
        'readable_created_at' => "string",
        'author_id' => "mixed",
        'author_name' => "string"
    ])]
    private function transformUserWorld(UserWorld $userWorld): array
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
