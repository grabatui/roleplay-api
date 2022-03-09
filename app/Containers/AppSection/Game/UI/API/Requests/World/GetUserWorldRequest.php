<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Models\UserWorld;
use App\Containers\AppSection\Game\Traits\IsUserWorldOwnerTrait;
use App\Containers\AppSection\User\Traits\IsOwnerTrait;
use App\Ship\Parents\Requests\Request;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property-read UserWorld|null $userWorld
 */
class GetUserWorldRequest extends Request
{
    use IsOwnerTrait;
    use IsUserWorldOwnerTrait;

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    protected array $urlParameters = [
        'userWorld',
    ];

    #[ArrayShape([
        'userWorld' => "array",
    ])]
    public function rules(): array
    {
        return [
            'userWorld' => [
                'required',
            ],
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess|isOwner',
            'isUserWorldOwner',
        ]);
    }
}