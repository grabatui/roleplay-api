<?php

namespace App\Containers\AppSection\User\Tasks\UserSettings;

use App\Containers\AppSection\User\Data\Repositories\UserSettingRepository;
use App\Ship\Criterias\ThisEqualThatCriteria;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class GetUserSettingsTask extends Task
{
    public function __construct(
        private UserSettingRepository $userSettingRepository
    ) {
    }

    public function run(string $id): Collection
    {
        $this->userSettingRepository->pushCriteria(
            new ThisEqualThatCriteria('user_id', $id)
        );

        return $this->userSettingRepository->get();
    }
}
