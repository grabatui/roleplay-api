<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Traits\IsGameOwnerTrait;
use App\Containers\AppSection\User\Traits\IsOwnerTrait;
use App\Ship\Parents\Requests\Request;

/**
 * @property-read Game|null $game
 */
class GetGameRequest extends Request
{
    use IsOwnerTrait;
    use IsGameOwnerTrait;

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    protected array $urlParameters = [
        'game',
    ];

    public function rules(): array
    {
        return [
            'game' => [
                'required',
            ],
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess|isOwner',
            'isGameOwner',
        ]);
    }
}