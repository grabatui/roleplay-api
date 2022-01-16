<?php

namespace App\Containers\AppSection\User\UI\API\Requests;

use App\Containers\AppSection\User\Traits\IsOwnerTrait;
use App\Ship\Parents\Requests\Request;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property-read int $id
 */
class GetUserSettingsRequest extends Request
{
    use IsOwnerTrait;

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    protected array $decode = [
        'id',
    ];

    protected array $urlParameters = [
        'id',
    ];

    #[ArrayShape(['id' => "string"])]
    public function rules(): array
    {
        return [
            'id' => 'required|exists:users,id',
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess|isOwner',
        ]);
    }
}
