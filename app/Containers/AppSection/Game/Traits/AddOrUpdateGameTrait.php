<?php

namespace App\Containers\AppSection\Game\Traits;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterFactory;
use App\Containers\AppSection\Game\Rules\GameSettingRule;
use App\Containers\AppSection\Game\Rules\GameSettingsRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

trait AddOrUpdateGameTrait
{
    #[ArrayShape([
        'code' => "array",
        'data' => "array",
        'data.*' => "\App\Containers\AppSection\Game\Rules\GameSettingRule[]",
        'data.*.code' => "string[]",
        'data.*.value' => "string"
    ])]
    protected function getDefaultDataRules(): array
    {
        $code = $this->request->get('code');

        $adapter = null;
        $hasRequiredSettings = false;
        if ($code) {
            $adapter = WorldAdapterFactory::getByCode($code);

            $hasRequiredSettings = $adapter->hasRequiredFormFields();
        }

        return [
            'code' => [
                'required',
                Rule::in(
                    WorldAdapterFactory::getCodes()
                ),
            ],
            'data' => $hasRequiredSettings
                ? [
                    'required',
                    new GameSettingsRule($adapter)
                ]
                : [],
            'data.*' => [
                new GameSettingRule($adapter),
            ],
            'data.*.code' => [
                'required',
                'distinct',
            ],
            'data.*.value' => 'nullable',
        ];
    }
}
