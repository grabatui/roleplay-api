<?php

namespace App\Containers\AppSection\Game\Actions\World;

use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Tasks\Game\AddGameTask;
use App\Containers\AppSection\Game\UI\API\Requests\World\AddGameRequest;
use App\Ship\Parents\Actions\Action;

class AddGameAction extends Action
{
    public function run(int $userId, AddGameRequest $request): Game
    {
        $worldCode = $request->get('code');

        $formSettings = [];
        foreach ($request->get('data') as $dataItem) {
            $formSettings[$dataItem['code']] = $dataItem['value'];
        }

        return app(AddGameTask::class)->run($userId, $worldCode, $formSettings);
    }
}
