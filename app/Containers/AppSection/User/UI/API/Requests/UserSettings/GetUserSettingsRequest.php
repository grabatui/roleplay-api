<?php

namespace App\Containers\AppSection\User\UI\API\Requests\UserSettings;

use App\Containers\AppSection\User\Traits\IsOwnerTrait;
use App\Ship\Parents\Requests\Request;

class GetUserSettingsRequest extends Request
{
    use IsOwnerTrait;

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess|isOwner',
        ]);
    }
}
