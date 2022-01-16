<?php

namespace App\Containers\AppSection\User\UI\API\Transformers;

use App\Containers\AppSection\User\Enum\Language;
use App\Containers\AppSection\User\Enum\UserSettingCode;
use App\Containers\AppSection\User\Models\UserSetting;
use App\Ship\Parents\Transformers\Transformer;
use JetBrains\PhpStorm\ArrayShape;

class UserSettingTransformer extends Transformer
{
    #[ArrayShape([
        'value' => "string",
        'code' => "string",
        'options' => "array[]",
    ])]
    public function transform(UserSetting $userSetting): array
    {
        $response = [
            'value' => $userSetting->value,
            'code' => $userSetting->code,
        ];

        switch ($userSetting->code) {
            case UserSettingCode::LANGUAGE:
                $response['options'] = array_map(
                    fn(string $language): array => [
                        'value' => $language,
                        'title' => __('appSection@user::main.language.' . $language),
                    ],
                    Language::ALL
                );
                break;
        }

        return $response;
    }
}
