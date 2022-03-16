<?php

namespace App\Containers\AppSection\Game\Tests\Unit;

use App\Containers\AppSection\Game\Actions\Entity\World\DndWorldAdapter;
use App\Containers\AppSection\Game\Enum\GameStatusEnum;
use App\Containers\AppSection\Game\Tests\TestCase;

class AddGameUnitTest extends TestCase
{
    public function test_happyPath(): void
    {
        $this->authorize();

        $response = $this->put(
            route('api_user_add_game'),
            [
                'code' => DndWorldAdapter::getCode(),
                'data' => [
                    [
                        'code' => DndWorldAdapter::TITLE,
                        'value' => 'Test title',
                    ],
                ],
            ],
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(200);

        $response->assertJsonPath(
            'data.status',
            GameStatusEnum::NEW
        );

        $response->assertJsonPath(
            'data.form_settings',
            [
                DndWorldAdapter::TITLE => 'Test title',
            ]
        );
    }

    public function test_error_validation(): void
    {
        $this->authorize();

        // Without settings
        $this->assertValidationErrorData(
            ['code' => DndWorldAdapter::getCode()],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data' => ['The data field is required.'],
                ],
            ]
        );

        // Empty settings
        $this->assertValidationErrorData(
            [
                'code' => DndWorldAdapter::getCode(),
                'data' => [],
            ],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data' => ['The data field is required.'],
                ],
            ]
        );

        // Wrong settings
        $this->assertValidationErrorData(
            [
                'code' => DndWorldAdapter::getCode(),
                'data' => [
                    [
                        'code' => 'wrongSettingCode',
                        'value' => 'wrongSettingValue',
                    ],
                ],
            ],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data' => ['Required settings are not filled: ' . DndWorldAdapter::TITLE],
                    'data.0' => ['Setting wrongSettingCode is unknown'],
                ],
            ]
        );

        // Empty required setting
        $this->assertValidationErrorData(
            [
                'code' => DndWorldAdapter::getCode(),
                'data' => [
                    [
                        'code' => DndWorldAdapter::TITLE,
                        'value' => '',
                    ],
                ],
            ],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data.0' => ['Setting ' . DndWorldAdapter::TITLE . ' is required'],
                ],
            ]
        );
    }

    public function test_wrongCredentials(): void
    {
        // User is not authorized
        $response = $this->get(
            route('api_user_get_games'),
            array_merge(
                $this->getApiHeaders(''),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonFragment([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);

        $this->authorize();

        // Without access token
        $response = $this->get(
            route('api_user_get_games'),
            array_merge(
                $this->getApiHeaders(''),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonFragment([
            'message' => 'An Exception occurred when trying to authenticate the User.',
            'errors' => [],
        ]);
    }

    private function assertValidationErrorData(array $requestData, array $expectedResponse): void
    {
        $response = $this->put(
            route('api_user_add_game'),
            $requestData,
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(422);
        $response->assertExactJson($expectedResponse);
    }
}
