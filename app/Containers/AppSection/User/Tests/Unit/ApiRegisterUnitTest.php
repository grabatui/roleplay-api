<?php

namespace App\Containers\AppSection\User\Tests\Unit;

use App\Containers\AppSection\User\Tests\ApiTestCase;
use Config;
use DB;

class ApiRegisterUnitTest extends ApiTestCase
{
    private string $accessToken;

    public function setUp(): void
    {
        parent::setUp();

        $response = $this->post(
            route('passport.token'),
            [
                'client_id' => Config::get('appSection-authentication.clients.web.id'),
                'client_secret' => Config::get('appSection-authentication.clients.web.secret'),
                'grant_type' => 'client_credentials',
            ]
        );

        $this->accessToken = $response->decodeResponseJson()->offsetGet('access_token');
    }

    public function test_happyPath(): void
    {
        $data = [
            'email' => 'test@test.test',
            'password' => 'newPassword',
            'name' => 'Test Name',
        ];

        $response = $this->post(
            route('api_user_register_user'),
            $data,
            $this->getApiHeaders($this->accessToken)
        );

        $response->assertStatus(200);

        $response->assertJsonPath('data.name', $data['name']);
    }

    public function test_withoutRequiredData(): void
    {
        // Without email
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            [
                'password' => 'newPassword',
                'name' => 'Test Name',
            ],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                ],
            ]
        );

        // Without password
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            [
                'email' => 'test@test.test',
                'name' => 'Test Name',
            ],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password field is required.'],
                ],
            ]
        );

        // Without name
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            [
                'email' => 'test@test.test',
                'password' => 'newPassword',
            ],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                ],
            ]
        );
    }

    public function test_wrongEmail(): void
    {
        $data = [
            'password' => 'newPassword',
            'name' => 'Test Name',
        ];

        // Wrong email
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            array_merge($data, [
                'email' => 'wrongEmail',
            ]),
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email must be a valid email address.'],
                ],
            ]
        );

        // Email too long
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            array_merge($data, [
                'email' => 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@test.test',
            ]),
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email must not be greater than 40 characters.'],
                ],
            ]
        );

        // Email already exists
        DB::table('users')->insert([
            'email' => 'alreadyExistsEmail@test.test',
            'password' => 'testPassword',
        ]);

        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            array_merge($data, [
                'email' => 'alreadyExistsEmail@test.test',
            ]),
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ]
        );
    }

    public function test_wrongPassword(): void
    {
        $data = [
            'name' => 'Test Name',
            'email' => 'test@test.test',
        ];

        // Password too short
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            array_merge($data, [
                'password' => 'pass',
            ]),
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password must be at least 6 characters.'],
                ],
            ]
        );

        // Password too long
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            array_merge($data, [
                'password' => 'passwordpasswordpasswordpasswordpasswordpasswordpasswordpasswordpasswordpassword',
            ]),
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password must not be greater than 30 characters.'],
                ],
            ]
        );
    }

    public function test_wrongName(): void
    {
        $data = [
            'email' => 'test@test.test',
            'password' => 'newPassword',
        ];

        // Password too short
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            array_merge($data, [
                'name' => 'n',
            ]),
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name must be at least 2 characters.'],
                ],
            ]
        );

        // Password too long
        $this->sendInvalidRequestAndAssertInvalidValidationResponse(
            array_merge($data, [
                'name' => 'namenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamename',
            ]),
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name must not be greater than 50 characters.'],
                ],
            ]
        );
    }

    private function sendInvalidRequestAndAssertInvalidValidationResponse(
        array $requestData,
        array $expectJson
    ): void {
        $response = $this->post(
            route('api_user_register_user'),
            $requestData,
            $this->getApiHeaders($this->accessToken)
        );

        $response->assertStatus(422);
        $response->assertJson($expectJson);
    }
}