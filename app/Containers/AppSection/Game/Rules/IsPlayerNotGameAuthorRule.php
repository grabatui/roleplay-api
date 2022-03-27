<?php

namespace App\Containers\AppSection\Game\Rules;

use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Tasks\Game\IsPlayerGameAuthorTask;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Contracts\Validation\Rule;

class IsPlayerNotGameAuthorRule implements Rule
{
    public function __construct(
        private ?Game $game,
        private ?User $player
    ) {
    }

    public function passes($attribute, $value)
    {
        if (! $this->game || ! $this->player) {
            return false;
        }

        return ! app(IsPlayerGameAuthorTask::class)->run(
            $this->game->id,
            $this->player->id
        );
    }

    public function message()
    {
        return __('appSection@game::error.player_is_an_game_author');
    }
}
