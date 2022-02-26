<?php

namespace App\Containers\AppSection\Game\UI\API\Transformers;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Setting;
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
            'code' => $world::getCode(),
            'title' => __('appSection@game::world.names.' . $world::getCode()),
            'form_settings' => array_map(
                fn(Setting $formSetting): array => $this->transformFormSetting($world::getCode(), $formSetting),
                $world->getSettings()
            ),
            'user_worlds' => $worldWithUserWorlds->getUserWorlds()->map(
                fn(UserWorld $userWorld): array => app(UserWorldTransformer::class)->transform($userWorld)
            ),
        ];
    }

    private function transformFormSetting(string $worldCode, Setting $formSetting): array
    {
        $result = [
            'code' => $formSetting->getCode(),
            'type' => $formSetting->getType()->getValue(),
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
