<?php

namespace App\Containers\AppSection\Game\Data\Seeders;

use App\Containers\AppSection\Game\Tasks\CreateWorldTask;
use App\Containers\AppSection\Game\Tasks\FindWorldByCodeTask;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Seeders\Seeder;

class DndWorldsSeeder_1 extends Seeder
{
    public function run(): void
    {
        try {
            app(FindWorldByCodeTask::class)->run('dnd');
        } catch (NotFoundException) {
            app(CreateWorldTask::class)->run('dnd', [
                [
                    'code' => 'title',
                    'type' => 'string',
                ],
                [
                    'code' => 'maxPlayersCount',
                    'type' => 'integer',
                    'hintExists' => true,
                ],
            ]);
        }
    }
}
