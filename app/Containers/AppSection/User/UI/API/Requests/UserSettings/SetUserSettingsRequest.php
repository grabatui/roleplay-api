<?php

namespace App\Containers\AppSection\User\UI\API\Requests\UserSettings;

use Apiato\Core\Abstracts\Requests\Request;
use App\Containers\AppSection\User\Enum\UserSettingCode;
use App\Containers\AppSection\User\Traits\IsOwnerTrait;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SetUserSettingsRequest extends Request
{
    use IsOwnerTrait;

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    #[ArrayShape([
        'items.*.code' => 'array',
        'items.*.value' => 'string',
    ])]
    public function rules(): array
    {
        return [
            'items.*.code' => [
                'required',
                Rule::in(UserSettingCode::ALL),
                'distinct',
            ],
            'items.*.value' => 'nullable',
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess|isOwner',
        ]);
    }
}
