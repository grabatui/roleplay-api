<?php

namespace App\Containers\AppSection\Game\UI\API\Requests\World;

use App\Containers\AppSection\Game\Enum\GameStatusEnum;
use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Rules\IsGameInStatusesRule;
use App\Containers\AppSection\Game\Rules\IsPlayerNotGameAuthorRule;
use App\Containers\AppSection\Game\Rules\IsPlayerNotInGameRule;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Requests\Request;

/**
 * @property-read Game|null $game
 * @property-read User|null $player
 */
class AddPlayerRequest extends Request
{
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    protected array $urlParameters = [
        'game',
        'player',
    ];

    public function rules(): array
    {
        return [
            'game' => [
                'required',
                new IsGameInStatusesRule(
                    $this->game,
                    [GameStatusEnum::NEW, GameStatusEnum::IN_PROGRESS]
                ),
            ],
            'player' => [
                'required',
                new IsPlayerNotInGameRule($this->game, $this->player),
                new IsPlayerNotGameAuthorRule($this->game, $this->player),
            ],
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
