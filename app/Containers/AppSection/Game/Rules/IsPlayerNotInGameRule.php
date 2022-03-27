<?php

namespace App\Containers\AppSection\Game\Rules;

use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Tasks\Game\IsPlayerInGameTask;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Contracts\Validation\Rule;

class IsPlayerNotInGameRule implements Rule
{
    public function __construct(
        private ?Game $game,
        private ?User $player
    ) {
    }

    public function passes($attribute, $value): bool
    {
        if (! $this->game || ! $this->player) {
            return false;
        }

        return ! app(IsPlayerInGameTask::class)->run(
            $this->player->id,
            $this->game->id
        );
    }

    public function message(): string
    {
        return __('appSection@game::error.player_already_in_game');
    }
}
