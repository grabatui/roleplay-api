<?php

namespace App\Containers\AppSection\Game\UI\API\Transformers;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field;
use App\Containers\AppSection\Game\Actions\Entity\WorldWithGames;
use App\Containers\AppSection\Game\Models\Game;
use App\Ship\Parents\Transformers\Transformer;
use JetBrains\PhpStorm\ArrayShape;

class WorldWithGamesTransformer extends Transformer
{
    #[ArrayShape([
        'code' => "string",
        'title' => "mixed",
        'form_settings' => "array",
        'games' => "array[]"
    ])]
    public function transform(WorldWithGames $worldWithGames): array
    {
        $world = $worldWithGames->getWorld();

        return [
            'code' => $world::getCode(),
            'title' => __('appSection@game::world.names.' . $world::getCode()),
            'form_settings' => array_map(
                fn(Field $formSetting): array => $this->transformFormSetting($world::getCode(), $formSetting),
                $world->getFormFields()
            ),
            'games' => $worldWithGames->getGames()->map(
                fn(Game $game): array => app(GameTransformer::class)->transform($game)
            ),
        ];
    }

    private function transformFormSetting(string $worldCode, Field $formSetting): array
    {
        $result = [
            'code' => $formSetting->getCode(),
            'type' => $formSetting->getType()->name,
        ];

        if ($formSetting->isHintExists()) {
            $result['hint'] = __(sprintf(
                'appSection@game::world.form_settings.hints.%s.%s',
                $worldCode,
                $formSetting->getCode()
            ));
        }

        return array_merge(
            $result,
            [
                'title' => __(sprintf(
                    'appSection@game::world.form_settings.titles.%s.%s',
                    $worldCode,
                    $formSetting->getCode()
                )),
            ]
        );
    }
}
