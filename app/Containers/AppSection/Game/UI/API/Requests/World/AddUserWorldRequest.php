<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterFactory;
use App\Containers\AppSection\Game\Rules\UserWorldSettingRule;
use App\Containers\AppSection\Game\Rules\UserWorldSettingsRule;
use App\Ship\Parents\Requests\Request;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class AddUserWorldRequest extends Request
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
        $isHasRequiredSettings = false;
        if ($code) {
            $adapter = WorldAdapterFactory::getByCode($code);

            $isHasRequiredSettings = $adapter->hasRequiredSettings();
        }

        return [
            'code' => [
                'required',
                Rule::in(
                    WorldAdapterFactory::getCodes()
                ),
            ],
            'data' => $isHasRequiredSettings
                ? [
                    'required',
                    new UserWorldSettingsRule($adapter)
                ]
                : [],
            'data.*' => [
                new UserWorldSettingRule($adapter),
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
