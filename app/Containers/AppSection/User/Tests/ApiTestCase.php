<?php

namespace App\Containers\AppSection\User\Tests;

use App\Containers\AppSection\User\Tests\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Class ApiTestCase.
 *
 * This is the container API TestCase class. Use this class to add your container specific API related helper functions.
 */
class ApiTestCase extends BaseTestCase
{
    protected const CLIENT_ID = 100;
    protected const CLIENT_SECRET = 'XXp8x4QK7d3J9R7OVRXWrhc19XPRroHTTKIbY8XX';

    private bool $testingFilesCreated = false;
    private string $publicFilePath;
    private string $privateFilePath;

    public function setUp(): void
    {
        parent::setUp();

        // create password grand client
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

        // make the clients credentials available as env variables
        Config::set('appSection-authentication.clients.web.id', self::CLIENT_ID);
        Config::set('appSection-authentication.clients.web.secret', self::CLIENT_SECRET);

        // create testing oauth keys files
        $this->publicFilePath = $this->createTestingKey('oauth-public.key');
        $this->privateFilePath = $this->createTestingKey('oauth-private.key');
    }

    protected function getApiHeaders(string $accessToken): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];
    }

    private function createTestingKey($fileName): string
    {
        $filePath = storage_path($fileName);

        if (! file_exists($filePath)) {
            $keysStubDirectory = __DIR__ . '/Stubs/';

            copy($keysStubDirectory . $fileName, $filePath);

            $this->testingFilesCreated = true;
        }

        return $filePath;
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // delete testing keys files if they were created for this test
        if ($this->testingFilesCreated) {
            unlink($this->publicFilePath);
            unlink($this->privateFilePath);
        }
    }
}
