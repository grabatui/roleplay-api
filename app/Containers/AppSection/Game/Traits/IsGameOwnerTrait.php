<?php

namespace App\Containers\AppSection\Game\Traits;

use App\Containers\AppSection\Game\Tasks\Game\CheckIfUserIsGameOwnerTask;

trait IsGameOwnerTrait
{
    public function isGameOwner(): bool
    {
        return app(CheckIfUserIsGameOwnerTask::class)->run(
            $this->user(),
            $this->game
        );
    }
}
