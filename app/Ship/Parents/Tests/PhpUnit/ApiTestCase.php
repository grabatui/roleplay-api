<?php

namespace App\Ship\Parents\Tests\PhpUnit;

use App\Containers\AppSection\User\Data\Factories\UserFactory;
use DB;
use Hash;
use Illuminate\Support\Facades\Config;

abstract class ApiTestCase extends TestCase
{
    protected const CLIENT_ID = 100;
    protected const CLIENT_SECRET = 'XXp8x4QK7d3J9R7OVRXWrhc19XPRroHTTKIbY8XX';

    protected ?string $accessToken = null;
    protected int $userId;

    private bool $testingFilesCreated = false;
    private string $publicFilePath;
    private string $privateFilePath;

    public function setUp(): void
    {
        parent::setUp();

        // make the clients credentials available as env variables
        Config::set('appSection-authentication.clients.web.id', self::CLIENT_ID);
        Config::set('appSection-authentication.clients.web.secret', self::CLIENT_SECRET);

        // create testing oauth keys files
        $this->publicFilePath = $this->createTestingKey('oauth-public.key');
        $this->privateFilePath = $this->createTestingKey('oauth-private.key');

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
                'provider' => 'users',
            ],
        ]);

        UserFactory::new()->create([
            'email' => 'test@test.test',
            'password' => Hash::make('testPassword'),
        ])->save();

        $this->userId = (int) DB::getPdo()->lastInsertId();
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

    protected function authorize(): void
    {
        $response = $this->post(
            route('api_authentication_client_web_login_proxy'),
            [
                'email' => 'test@test.test',
                'password' => 'testPassword',
            ]
        );

        $this->accessToken = $response->decodeResponseJson()->offsetGet('access_token');
    }
}
