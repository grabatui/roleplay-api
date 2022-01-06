<?php

namespace App\Containers\AppSection\Authentication\Tests\Unit;

use App\Containers\AppSection\Authentication\Tests\ApiTestCase;
use App\Containers\AppSection\User\Data\Factories\UserFactory;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiPassportPrivateTokenTest extends ApiTestCase
{
    protected const CLIENT_ID = 200;

    private string $username = 'test@test.test';
    private string $password = 'strongTestPassword';

    public function setUp(): void
    {
        parent::setUp();

        DB::table('oauth_clients')->insert([
            [
                'id' => self::CLIENT_ID,
                'secret' => self::CLIENT_SECRET,
                'name' => 'Testing',
                'redirect' => 'http://localhost',
                'password_client' => '1',
                'personal_access_client' => '0',
                'revoked' => '0',
                'provider' => 'users',
            ],
        ]);

        UserFactory::new()->create([
            'email' => $this->username,
            'password' => Hash::make($this->password),
        ])->save();
    }

    public function test_happyPath(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => self::CLIENT_ID,
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'password',
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonPath('token_type', 'Bearer');
        $response->assertJsonPath('expires_in', 86400);

        $this->assertNotEmpty(
            $response->decodeResponseJson()->offsetGet('access_token')
        );
        $this->assertNotEmpty(
            $response->decodeResponseJson()->offsetGet('refresh_token')
        );
    }

    public function test_wrongClientId(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => 'wrongClientId',
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'password',
            ]
        );

        $response->assertStatus(401);

        $response->assertJson([
            'error' => 'invalid_client',
            'error_description' => 'Client authentication failed',
            'message' => 'Client authentication failed',
        ]);
    }

    public function test_wrongClientSecret(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => self::CLIENT_ID,
                'client_secret' => 'wrongClientSecret',
                'grant_type' => 'password',
            ]
        );

        $response->assertStatus(401);

        $response->assertJson([
            'error' => 'invalid_client',
            'error_description' => 'Client authentication failed',
            'message' => 'Client authentication failed',
        ]);
    }

    public function test_wrongGrantType(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => self::CLIENT_ID,
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'wrongGrantType',
            ]
        );

        $response->assertStatus(400);

        $response->assertJson([
            'error' => 'unsupported_grant_type',
            'error_description' => 'The authorization grant type is not supported by the authorization server.',
            'message' => 'The authorization grant type is not supported by the authorization server.',
            'hint' => 'Check that all required parameters have been provided',
        ]);
    }

    public function test_wrongUsername(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'username' => 'wrongUsername',
                'password' => $this->password,
                'client_id' => self::CLIENT_ID,
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'password',
            ]
        );

        $response->assertStatus(400);

        $response->assertJson([
            'error' => 'invalid_grant',
            'error_description' => 'The user credentials were incorrect.',
            'message' => 'The user credentials were incorrect.',
        ]);
    }

    public function test_wrongPassword(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'username' => $this->username,
                'password' => 'wrongPassword',
                'client_id' => self::CLIENT_ID,
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'password',
            ]
        );

        $response->assertStatus(400);

        $response->assertJson([
            'error' => 'invalid_grant',
            'error_description' => 'The user credentials were incorrect.',
            'message' => 'The user credentials were incorrect.',
        ]);
    }
}