<?php

namespace App\Containers\AppSection\Game\UI\API\Controllers;

use App\Containers\AppSection\Game\Actions\World\AddGameAction;
use App\Containers\AppSection\Game\Actions\World\GetGamesByWorldsAction;
use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\UI\API\Requests\World\AddGameRequest;
use App\Containers\AppSection\Game\UI\API\Requests\World\GetGameRequest;
use App\Containers\AppSection\Game\UI\API\Requests\World\GetGamesRequest;
use App\Containers\AppSection\Game\UI\API\Transformers\GameTransformer;
use App\Containers\AppSection\Game\UI\API\Transformers\WorldWithGamesTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Auth;

class Controller extends ApiController
{
    public function getGames(GetGamesRequest $request): array
    {
        $userWorlds = app(GetGamesByWorldsAction::class)->run(Auth::id());

        return $this->transform($userWorlds, WorldWithGamesTransformer::class);
    }

    public function getGame(Game $game, GetGameRequest $request): array
    {
        return $this->transform($game, GameTransformer::class);
    }

    public function addGame(AddGameRequest $request): array
    {
        $userWorld = app(AddGameAction::class)->run(Auth::id(), $request);

        return $this->transform($userWorld, GameTransformer::class);
    }
}
