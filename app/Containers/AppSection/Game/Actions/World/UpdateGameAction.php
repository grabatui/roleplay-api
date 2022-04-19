<?php

namespace App\Containers\AppSection\Game\Actions\World;

use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Tasks\Game\UpdateGameTask;
use App\Containers\AppSection\Game\UI\API\Requests\World\UpdateGameRequest;

class UpdateGameAction
{
    public function run(int $gameId, UpdateGameRequest $request): Game
    {
        $worldCode = $request->get('code');

        $formSettings = [];
        foreach ($request->get('data') as $dataItem) {
            $formSettings[$dataItem['code']] = $dataItem['value'];
        }

        return app(UpdateGameTask::class)->run($gameId, $worldCode, $formSettings);
    }
}
