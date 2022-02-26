<?php

namespace App\Containers\AppSection\Game\Actions\Entity;

use Apiato\Core\Traits\HasResourceKeyTrait;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use Illuminate\Support\Collection;

class WorldWithUserWorlds
{
    use HasResourceKeyTrait;

    /**
     * @param WorldAdapterInterface $world
     * @param int $userId
     * @param Collection $userWorlds
     */
    public function __construct(
        private WorldAdapterInterface $world,
        private int $userId,
        private Collection $userWorlds
    ) {
    }

    public function getWorld(): WorldAdapterInterface
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
