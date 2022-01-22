<?php

namespace App\Containers\AppSection\User\Actions\UserSettings;

use App\Containers\AppSection\User\Tasks\Entity\UserSettingDto;
use App\Containers\AppSection\User\Tasks\UserSettings\SetUserSettingsTask;
use App\Containers\AppSection\User\UI\API\Requests\UserSettings\SetUserSettingsRequest;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Collection;

class SetUserSettingsAction extends Action
{
    public function run(int $userId, SetUserSettingsRequest $request): void
    {
        app(SetUserSettingsTask::class)->run(
            $userId,
            $this->makeUserSettings(
                $request->get('items')
            )
        );
    }

    private function makeUserSettings(array $rawUserSettings): Collection
    {
        return collect(
            array_map(
                fn(array $rawUserSetting): UserSettingDto => new UserSettingDto(
                    $rawUserSetting['code'],
                    $rawUserSetting['value']
                ),
                $rawUserSettings
            )
        );
    }
}
