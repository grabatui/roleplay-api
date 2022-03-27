<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterFactory;
use App\Containers\AppSection\Game\Rules\GameSettingRule;
use App\Containers\AppSection\Game\Rules\GameSettingsRule;
use App\Ship\Parents\Requests\Request;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class AddGameRequest extends Request
{
    protected array $access = [
        'roles' => '',
        'permissions' => '',
    ];

    #[ArrayShape([
        'code' => "array",
        'data' => "array",
        'data.*' => "array",
        'data.*.code' => "array",
        'data.*.value' => "string",
    ])]
    public function rules(): array
    {
        $code = $this->request->get('code');

        $adapter = null;
        $hasRequiredSettings = false;
        if ($code) {
            $adapter = WorldAdapterFactory::getByCode($code);

            $hasRequiredSettings = $adapter->hasRequiredSettings();
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

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
