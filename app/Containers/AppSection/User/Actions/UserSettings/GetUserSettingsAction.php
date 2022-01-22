<?php

namespace App\Containers\AppSection\User\Actions\UserSettings;

use App\Containers\AppSection\User\Enum\Language;
use App\Containers\AppSection\User\Enum\UserSettingCode;
use App\Containers\AppSection\User\Models\UserSetting;
use App\Containers\AppSection\User\Tasks\UserSettings\GetUserSettingsTask;
use App\Containers\AppSection\User\UI\API\Requests\UserSettings\GetUserSettingsRequest;
use App\Ship\Exceptions\InternalErrorException;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Collection;
use UnhandledMatchError;

class GetUserSettingsAction extends Action
{
    public function run(int $userId, GetUserSettingsRequest $request): Collection
    {
        $userSettings = app(GetUserSettingsTask::class)
            ->run($userId)
            ->keyBy('code');

        $userLanguage = $request->getLocale() ?: Language::DEFAULT;

        foreach (UserSettingCode::ALL as $code) {
            if (! $userSettings->offsetExists($code)) {
                $userSettings->offsetSet(
                    $code,
                    $this->getDefaultUserSetting($code, $userLanguage, $userId)
                );
            }
        }

        return $userSettings;
    }

    private function getDefaultUserSetting(string $code, string $language, int $userId): UserSetting
    {
        try {
            return match ($code) {
                UserSettingCode::LANGUAGE => $this->makeDefaultUserSetting($code, $language, $userId),
            };
        } catch (UnhandledMatchError $exception) {
            throw new InternalErrorException('Default User setting is not set');
        }
    }

    private function makeDefaultUserSetting(string $code, mixed $defaultValue, int $userId): UserSetting
    {
        return new UserSetting([
            'user_id' => $userId,
            'code' => $code,
            'value' => $defaultValue,
        ]);
    }
}
