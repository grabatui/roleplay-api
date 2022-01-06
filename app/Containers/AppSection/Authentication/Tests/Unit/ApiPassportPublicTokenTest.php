<?php

namespace App\Containers\AppSection\Authentication\Tests\Unit;

use App\Containers\AppSection\Authentication\Tests\ApiTestCase;
use Config;

class ApiPassportPublicTokenTest extends ApiTestCase
{
    public function test_happyPath(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'client_id' => Config::get('appSection-authentication.clients.web.id'),
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'client_credentials',
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonPath('token_type', 'Bearer');
        $response->assertJsonPath('expires_in', 86400);

        $this->assertNotEmpty(
            $response->decodeResponseJson()->offsetGet('access_token')
        );
    }

    public function test_wrongClientId(): void
    {
        $response = $this->post(
            route('passport.token'),
            [
                'client_id' => 'wrongClientId',
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'client_credentials',
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
                'client_id' => Config::get('appSection-authentication.clients.web.id'),
                'client_secret' => 'wrongClientSecret',
                'grant_type' => 'client_credentials',
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
                'client_id' => Config::get('appSection-authentication.clients.web.id'),
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
}