<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Traits\AddOrUpdateGameTrait;
use App\Ship\Parents\Requests\Request;
use JetBrains\PhpStorm\ArrayShape;

class AddGameRequest extends Request
{
    use AddOrUpdateGameTrait;

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
        return $this->getDefaultDataRules();
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
