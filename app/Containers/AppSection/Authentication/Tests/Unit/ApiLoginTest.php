<?php

namespace App\Containers\AppSection\Authentication\Tests\Unit;

use App\Containers\AppSection\Authentication\Tests\ApiTestCase;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Testing\TestResponse;

class ApiLoginTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        DB::table('users')->insert([
            'email' => 'test@test.test',
            'password' => Hash::make('testPassword'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function test_happyPath(): void
    {
        $response = $this->authorizeWithCredentials('test@test.test', 'testPassword');

        $accessToken = $response->decodeResponseJson()->offsetGet('access_token');

        $this->assertNotEmpty($accessToken);

        $response = $this->get(
            route('api_user_get_authenticated_user'),
            $this->getApiHeaders($accessToken)
        );

        $data = $response->decodeResponseJson();

        $this->assertNotEmpty(
            $data->json('data.id')
        );

        $this->assertEquals(
            'test@test.test',
            $data->json('data.email')
        );
    }

    public function test_invalidCredentials(): void
    {
        $response = $this->authorizeWithCredentials('wrong@email.test', 'testPassword');

        $response->assertStatus(302);

        $response = $this->authorizeWithCredentials('test@test.test', 'wrongPassword');

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'The user credentials were incorrect.',
            'errors' => [],
        ]);
    }

    public function test_usingWrongAccessToken(): void
    {
        $response = $this->get(
            route('api_user_get_authenticated_user'),
            $this->getApiHeaders('wrongAccessToken')
        );

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);

        $response = $this->get(
            route('api_user_get_authenticated_user'),
            $this->getApiHeaders('')
        );

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);
    }

    private function authorizeWithCredentials(string $email, string $decodedPassword): TestResponse
    {
        return $this->post(
            route('api_authentication_client_web_login_proxy'),
            [
                'email' => $email,
                'password' => $decodedPassword,
            ]
        );
    }
}