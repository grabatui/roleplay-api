<?php

namespace App\Containers\AppSection\Game\Actions\World;

use App\Containers\AppSection\Game\Models\UserWorld;
use App\Containers\AppSection\Game\Tasks\UserWorld\AddUserWorldTask;
use App\Containers\AppSection\Game\UI\API\Requests\World\AddUserWorldRequest;
use App\Ship\Parents\Actions\Action;

class AddUserWorldAction extends Action
{
    public function run(int $userId, AddUserWorldRequest $request): UserWorld
    {
        $worldCode = $request->get('code');

        $formSettings = [];
        foreach ($request->get('data') as $dataItem) {
            $formSettings[$dataItem['code']] = $dataItem['value'];
        }

        return app(AddUserWorldTask::class)->run($userId, $worldCode, $formSettings);
    }
}
