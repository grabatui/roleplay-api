<?php

namespace App\Containers\AppSection\Game\Traits;

use App\Containers\AppSection\Game\Tasks\UserWorld\CheckIfUserHasAccessToUserWorldTask;

trait IsUserWorldOwnerTrait
{
    public function isUserWorldOwner(): bool
    {
        return app(CheckIfUserHasAccessToUserWorldTask::class)->run(
            $this->user(),
            $this->userWorld
        );
    }
}
