<?php

namespace App\Containers\AppSection\Game\Tests;

use App\Ship\Parents\Tests\PhpUnit\ApiTestCase as ShipTestCase;
use JetBrains\PhpStorm\ArrayShape;

abstract class TestCase extends ShipTestCase
{
    #[ArrayShape([
        'Accept' => "string",
        'Authorization' => "string"
    ])]
    protected function getApiHeaders(string $accessToken): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];
    }

    public function makeWrongCredentialsTests(string $url, string $method = 'get'): void
    {
        $headers = array_merge(
            $this->getApiHeaders(''),
            ['Accept-Language' => 'en']
        );

        // User is not authorized
        $response = $method === 'get'
            ? $this->get($url, $headers)
            : $this->$method($url, [], $headers);

        $response->assertJsonFragment([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);

        $this->authorize();

        // Without access token
        $response = $method === 'get'
            ? $this->get($url, $headers)
            : $this->$method($url, [], $headers);

        $response->assertJsonFragment([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);
    }
}
