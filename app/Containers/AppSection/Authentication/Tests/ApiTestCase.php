<?php

namespace App\Containers\AppSection\Authentication\Tests;

use App\Ship\Parents\Tests\PhpUnit\ApiTestCase as ShipApiTestCase;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class ApiTestCase.
 *
 * This is the container API TestCase class. Use this class to add your container specific API related helper functions.
 */
class ApiTestCase extends ShipApiTestCase
{
    #[ArrayShape([
        'Accept' => "string",
        'Authorization' => "string",
    ])]
    protected function getApiHeaders(string $accessToken): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];
    }
}
