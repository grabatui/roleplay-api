<?php

namespace App\Containers\AppSection\User\Tasks\UserSettings;

use App\Containers\AppSection\User\Data\Repositories\UserSettingRepository;
use App\Containers\AppSection\User\Tasks\Entity\UserSettingDto;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class SetUserSettingsTask extends Task
{
    public function __construct(
        private UserSettingRepository $userSettingRepository
    ) {
    }

    public function run(int $userId, Collection $userSettingsForSave): void
    {
        $userSettingsForSave->each(
            fn(UserSettingDto $userSetting): mixed => $this->userSettingRepository->updateOrCreate(
                [
                    'user_id' => $userId,
                    'code' => $userSetting->getCode(),
                ],
                ['value' => $userSetting->getValue()]
            )
        );
    }
}