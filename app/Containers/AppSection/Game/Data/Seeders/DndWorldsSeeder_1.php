<?php

namespace App\Containers\AppSection\Game\Data\Seeders;

use App\Containers\AppSection\Game\Enum\WorldFormSettingCodeEnum;
use App\Containers\AppSection\Game\Tasks\World\CreateWorldTask;
use App\Containers\AppSection\Game\Tasks\World\FindWorldByCodeTask;
use App\Containers\AppSection\Game\Tasks\World\UpdateWorldTask;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Seeders\Seeder;

class DndWorldsSeeder_1 extends Seeder
{
    public function run(): void
    {
        $formFields = [
            [
                'code' => WorldFormSettingCodeEnum::TITLE,
                'type' => 'string',
            ],
            [
                'code' => WorldFormSettingCodeEnum::MAX_PLAYERS_COUNT,
                'type' => 'integer',
                'hintExists' => true,
            ],
        ];

        try {
            app(FindWorldByCodeTask::class)->run('dnd');

            app(UpdateWorldTask::class)->run('dnd', $formFields);
        } catch (NotFoundException) {
            app(CreateWorldTask::class)->run('dnd', $formFields);
        }
    }
}
