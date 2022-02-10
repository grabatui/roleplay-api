<?php

namespace App\Containers\AppSection\Authentication\Tests;

use App\Ship\Parents\Tests\PhpUnit\ApiTestCase as ShipApiTestCase;
use DB;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class ApiTestCase.
 *
 * This is the container API TestCase class. Use this class to add your container specific API related helper functions.
 */
class ApiTestCase extends ShipApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        DB::table('oauth_clients')->insert([
            [
                'id' => self::CLIENT_ID,
                'name' => 'Testing',
                'secret' => self::CLIENT_SECRET,
                'redirect' => 'http://localhost',
                'password_client' => '1',
                'personal_access_client' => '0',
                'revoked' => '0',
            ],
        ]);
    }

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
