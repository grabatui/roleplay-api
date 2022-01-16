<?php

namespace App\Containers\AppSection\User\Tests\Unit;

use App\Containers\AppSection\User\Enum\UserSettingCode;
use App\Containers\AppSection\User\Tests\ApiTestCase;
use Carbon\Carbon;
use Config;
use DB;
use Hash;
use Illuminate\Testing\TestResponse;
use Vinkla\Hashids\Facades\Hashids;

class ApiGetUserSettingsUnitTest extends ApiTestCase
{
    private string $accessToken;
    private int $userId;

    public function setUp(): void
    {
        parent::setUp();

        DB::table('users')->insert([
            'email' => 'test@test.test',
            'password' => Hash::make('testPassword'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $this->userId = (int) DB::getPdo()->lastInsertId();

        $response = $this->post(
            route('api_authentication_client_web_login_proxy'),
            [
                'email' => 'test@test.test',
                'password' => 'testPassword',
            ]
        );

        $this->accessToken = $response->decodeResponseJson()->offsetGet('access_token');
    }

    public function test_happyPath_withoutExists(): void
    {
        $response = $this->get(
            route('api_user_get_authenticated_user'),
            $this->getApiHeaders($this->accessToken)
        );

        $response = $this->get(
            route('api_user_get_user_settings', $response->decodeResponseJson()->json('data.id')),
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
        DB::table('user_settings')->insert([
            'user_id' => $this->userId,
            'code' => UserSettingCode::LANGUAGE,
            'value' => json_encode('ru'),
        ]);

        $response = $this->get(
            route('api_user_get_authenticated_user'),
            $this->getApiHeaders($this->accessToken)
        );

        $response = $this->get(
            route('api_user_get_user_settings', $response->decodeResponseJson()->json('data.id')),
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

    public function test_wrongData(): void
    {
        // Without access token
        $response = $this->get(
            route('api_user_get_user_settings', 1),
            array_merge(
                $this->getApiHeaders(''),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonFragment([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);

        // Not encoded user id
        $response = $this->get(
            route('api_user_get_user_settings', 1),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonFragment([
            'message' => 'The given data was invalid.',
            'errors' => [
                'id' => ['The id field is required.'],
            ],
        ]);

        // Wrong user id
        $response = $this->get(
            route('api_user_get_user_settings', Hashids::encode('wrongId')),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(404);
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
