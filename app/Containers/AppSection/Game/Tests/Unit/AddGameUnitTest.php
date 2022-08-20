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
                        'code' => DndWorldAdapter::FORM_TITLE,
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
                DndWorldAdapter::FORM_TITLE => 'Test title',
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
                    'data' => ['Required settings are not filled: ' . DndWorldAdapter::FORM_TITLE],
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
                        'code' => DndWorldAdapter::FORM_TITLE,
                        'value' => '',
                    ],
                ],
            ],
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data.0' => ['Setting ' . DndWorldAdapter::FORM_TITLE . ' is required'],
                ],
            ]
        );
    }

    public function test_wrongCredentials(): void
    {
        $this->makeWrongCredentialsTests(
            route('api_user_get_games')
        );
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
