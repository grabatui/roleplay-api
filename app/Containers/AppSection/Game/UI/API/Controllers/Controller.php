<?php

namespace App\Containers\AppSection\Game\UI\API\Controllers;

use App\Containers\AppSection\Game\Actions\World\GetUserWorldsByWorldsAction;
use App\Containers\AppSection\Game\UI\API\Requests\GetUserWorldsRequest;
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
}
