<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\User\Models\User;

class CheckIfUserIsGameOwnerTask
{
    public function run(User $user, ?Game $game): bool
    {
        return $game && $game->author_id === $user->id;
    }
}
