<?php

namespace App\Containers\AppSection\Game\Tasks\UserWorld;

use App\Containers\AppSection\Game\Models\UserWorld;
use App\Containers\AppSection\User\Models\User;

class CheckIfUserHasAccessToUserWorldTask
{
    public function run(User $user, ?UserWorld $userWorld): bool
    {
        return $userWorld && $userWorld->author_id === $user->id;
    }
}
