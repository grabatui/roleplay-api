<?php

namespace App\Containers\AppSection\Game\Actions\Entity;

use Apiato\Core\Traits\HasResourceKeyTrait;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use Illuminate\Support\Collection;

class WorldWithGames
{
    use HasResourceKeyTrait;

    public function __construct(
        private readonly WorldAdapterInterface $world,
        private readonly int $userId,
        private readonly Collection $games
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
