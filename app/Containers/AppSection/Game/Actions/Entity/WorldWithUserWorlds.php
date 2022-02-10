<?php

namespace App\Containers\AppSection\Game\Actions\Entity;

use Apiato\Core\Traits\HasResourceKeyTrait;
use App\Containers\AppSection\Game\Models\UserWorld;
use App\Containers\AppSection\Game\Models\World;
use Illuminate\Support\Collection;

class WorldWithUserWorlds
{
    use HasResourceKeyTrait;

    /**
     * @param Collection|UserWorld[] $userWorlds
     */
    public function __construct(
        private World $world,
        private int $userId,
        private Collection $userWorlds
    ) {
    }

    public function getWorld(): World
    {
        return $this->world;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserWorlds(): Collection
    {
        return $this->userWorlds;
    }
}
