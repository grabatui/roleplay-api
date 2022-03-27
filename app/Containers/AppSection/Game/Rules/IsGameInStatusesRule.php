<?php

namespace App\Containers\AppSection\Game\Rules;

use App\Containers\AppSection\Game\Models\Game;
use Illuminate\Contracts\Validation\Rule;

class IsGameInStatusesRule implements Rule
{
    /**
     * @param Game $game
     * @param string[] $statuses
     */
    public function __construct(
        private Game $game,
        private array $statuses
    ) {
    }

    public function passes($attribute, $value): bool
    {
        return in_array($this->game->status, $this->statuses);
    }

    public function message(): string
    {
        return __('appSection@game::error.game_is_completed');
    }
}
