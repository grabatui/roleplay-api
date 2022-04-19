<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Enum\GameStatusEnum;
use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Rules\IsGameInStatusesRule;
use App\Containers\AppSection\Game\Traits\AddOrUpdateGameTrait;
use App\Containers\AppSection\Game\Traits\IsGameOwnerTrait;
use App\Ship\Parents\Requests\Request;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property-read Game|null $game
 */
class UpdateGameRequest extends Request
{
    use AddOrUpdateGameTrait;
    use IsGameOwnerTrait;

    protected array $access = [
        'roles' => '',
        'permissions' => '',
    ];

    protected array $urlParameters = [
        'game',
    ];

    #[ArrayShape([
        'game' => "array",
        'code' => "array",
        'data' => "array",
        'data.*' => "array",
        'data.*.code' => "array",
        'data.*.value' => "string",
    ])]
    public function rules(): array
    {
        return array_merge(
            [
                'game' => [
                    'required',
                    new IsGameInStatusesRule(
                        $this->game,
                        [GameStatusEnum::NEW, GameStatusEnum::IN_PROGRESS]
                    ),
                ],
            ],
            $this->getDefaultDataRules()
        );
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
            'isGameOwner',
        ]);
    }
}
