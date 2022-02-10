<?php

namespace App\Containers\AppSection\User\Tests\Unit;

use App\Containers\AppSection\User\Enum\UserSettingCode;
use App\Containers\AppSection\User\Tests\ApiTestCase;
use Carbon\Carbon;
use DB;
use Hash;
use JetBrains\PhpStorm\ArrayShape;
use stdClass;

class ApiSetUserSettingsUnitTest extends ApiTestCase
{
    public function test_happyPath_withoutExists(): void
    {
        $this->authorize();

        $this->assertEmpty(
            $this->getUserSettingsFromDatabase()
        );

        $saveUserSettings = $this->makeUserSettings([
            [UserSettingCode::LANGUAGE, 'ru'],
        ]);

        $response = $this->post(
            route('api_user_set_user_settings'),
            $saveUserSettings,
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(204);

        $this->assertEquals(
            $saveUserSettings['items'],
            $this->getUserSettingsFromDatabase()
        );
    }

    public function test_happyPath_withExists(): void
    {
        $this->authorize();

        DB::table('user_settings')->insert([
            [
                'user_id' => $this->userId,
                'code' => UserSettingCode::LANGUAGE,
                'value' => json_encode('en'),
            ]
        ]);

        $saveUserSettings = $this->makeUserSettings([
            [UserSettingCode::LANGUAGE, 'en'],
        ]);

        $this->assertEquals(
            $saveUserSettings['items'],
            $this->getUserSettingsFromDatabase()
        );

        $saveUserSettings = $this->makeUserSettings([
            [UserSettingCode::LANGUAGE, 'ru'],
        ]);

        $response = $this->post(
            route('api_user_set_user_settings'),
            $saveUserSettings,
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(204);

        $this->assertEquals(
            $saveUserSettings['items'],
            $this->getUserSettingsFromDatabase()
        );
    }

    public function test_wrongCredentials(): void
    {
        $saveUserSettings = $this->makeUserSettings([
            [UserSettingCode::LANGUAGE, 'en'],
        ]);

        // User is not authorized
        $response = $this->post(
            route('api_user_set_user_settings'),
            $saveUserSettings,
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
        $response = $this->post(
            route('api_user_set_user_settings'),
            $saveUserSettings,
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

    public function test_wrongData(): void
    {
        $this->authorize();

        // Code's duplicate
        $saveUserSettings = $this->makeUserSettings([
            [UserSettingCode::LANGUAGE, 'en'],
            [UserSettingCode::LANGUAGE, 'ru'],
        ]);

        $response = $this->post(
            route('api_user_set_user_settings'),
            $saveUserSettings,
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'items.0.code' => ['The items.0.code field has a duplicate value.'],
                'items.1.code' => ['The items.1.code field has a duplicate value.'],
            ],
        ]);

        // Unknown user setting code
        $saveUserSettings = $this->makeUserSettings([
            ['wrongCOde', 'en'],
        ]);

        $response = $this->post(
            route('api_user_set_user_settings'),
            $saveUserSettings,
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'items.0.code' => ['The selected items.0.code is invalid.'],
            ],
        ]);

        // Empty user setting code
        $saveUserSettings = $this->makeUserSettings([
            ['', 'en'],
        ]);

        $response = $this->post(
            route('api_user_set_user_settings'),
            $saveUserSettings,
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'items.0.code' => ['The items.0.code field is required.'],
            ],
        ]);
    }

    private function getUserSettingsFromDatabase(): array
    {
        $databaseUserSettings = DB::table('user_settings')
            ->where('user_id', $this->userId)
            ->get(['code', 'value']);

        return $databaseUserSettings
            ->map(
                static fn (stdClass $userSetting): array => [
                    'code' => $userSetting->code,
                    'value' => json_decode($userSetting->value, true),
                ]
            )
            ->toArray();
    }

    #[ArrayShape(['items' => "array"])]
    private function makeUserSettings(array $rawItems): array
    {
        $items = [];
        foreach ($rawItems as $rawItem) {
            [$code, $value] = $rawItem;

            $items[] = [
                'code' => $code,
                'value' => $value,
            ];
        }

        return ['items' => $items];
    }
}