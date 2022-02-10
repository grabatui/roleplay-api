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
}
