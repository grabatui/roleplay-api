<?php

namespace App\Containers\AppSection\User\Tests\Unit;

use App\Containers\AppSection\User\Enum\UserSettingCode;
use App\Containers\AppSection\User\Tests\ApiTestCase;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Testing\TestResponse;

class ApiGetUserSettingsUnitTest extends ApiTestCase
{
    public function test_happyPath_withoutExists(): void
    {
        $this->authorize();

        $response = $this->get(
            route('api_user_get_user_settings'),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $this->assertResponseUserSettings(
            [
                UserSettingCode::LANGUAGE => 'en',
            ],
            $response
        );
    }

    public function test_happyPath_withExists(): void
    {
        $this->authorize();

        DB::table('user_settings')->insert([
            'user_id' => $this->userId,
            'code' => UserSettingCode::LANGUAGE,
            'value' => json_encode('ru'),
        ]);

        $response = $this->get(
            route('api_user_get_user_settings'),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $this->assertResponseUserSettings(
            [
                UserSettingCode::LANGUAGE => 'ru',
            ],
            $response
        );
    }

    public function test_wrongCredentials(): void
    {
        // User is not authorized
        $response = $this->get(
            route('api_user_get_user_settings'),
            array_merge(
                $this->getApiHeaders(''),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonFragment([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);

        $this->authorize();

        // Without access token
        $response = $this->get(
            route('api_user_get_user_settings'),
            array_merge(
                $this->getApiHeaders(''),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonFragment([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);
    }

    private function assertResponseUserSettings(array $expectValues, TestResponse $response): void
    {
        $responseValues = [];
        foreach ($response->json('data') as $userSetting) {
            $responseValues[$userSetting['code']] = $userSetting['value'];
        }

        $this->assertCount(count(UserSettingCode::ALL), $responseValues);

        foreach ($expectValues as $code => $expectValue) {
            $this->assertEquals($expectValue, $responseValues[$code]);
        }
    }
}
