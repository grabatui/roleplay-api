<?php

namespace App\Containers\AppSection\Game\Actions\Entity;

use Apiato\Core\Traits\HasResourceKeyTrait;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use Illuminate\Support\Collection;

class WorldWithGames
{
    use HasResourceKeyTrait;

    /**
     * @param WorldAdapterInterface $world
     * @param int $userId
     * @param Collection $games
     */
    public function __construct(
        private WorldAdapterInterface $world,
        private int $userId,
        private Collection $games
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

    public function getGames(): Collection
    {
        return $this->games;
    }
}
