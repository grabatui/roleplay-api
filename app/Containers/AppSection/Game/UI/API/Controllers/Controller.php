<?php

namespace App\Containers\AppSection\Game\UI\API\Controllers;

use App\Containers\AppSection\Game\Actions\World\AddUserWorldAction;
use App\Containers\AppSection\Game\Actions\World\GetUserWorldsByWorldsAction;
use App\Containers\AppSection\Game\Models\UserWorld;
use App\Containers\AppSection\Game\UI\API\Requests\World\AddUserWorldRequest;
use App\Containers\AppSection\Game\UI\API\Requests\World\GetUserWorldRequest;
use App\Containers\AppSection\Game\UI\API\Requests\World\GetUserWorldsRequest;
use App\Containers\AppSection\Game\UI\API\Transformers\UserWorldTransformer;
use App\Containers\AppSection\Game\UI\API\Transformers\WorldWithUserWorldsTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Auth;

class Controller extends ApiController
{
    public function getUserWorlds(GetUserWorldsRequest $request): array
    {
        $userWorlds = app(GetUserWorldsByWorldsAction::class)->run(Auth::id());

        return $this->transform($userWorlds, WorldWithUserWorldsTransformer::class);
    }

    public function getUserWorld(UserWorld $userWorld, GetUserWorldRequest $request): array
    {
        return $this->transform($userWorld, UserWorldTransformer::class);
    }

    public function addUserWorld(AddUserWorldRequest $request): array
    {
        $userWorld = app(AddUserWorldAction::class)->run(Auth::id(), $request);

        return $this->transform($userWorld, UserWorldTransformer::class);
    }
}
