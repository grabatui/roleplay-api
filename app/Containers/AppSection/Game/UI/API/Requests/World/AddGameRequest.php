<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Traits\AddOrUpdateGameTrait;
use App\Ship\Parents\Requests\Request;

class AddGameRequest extends Request
{
    use AddOrUpdateGameTrait;

    protected array $access = [
        'roles' => '',
        'permissions' => '',
    ];

    public function rules(): array
    {
        return $this->getDefaultDataRules();
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
