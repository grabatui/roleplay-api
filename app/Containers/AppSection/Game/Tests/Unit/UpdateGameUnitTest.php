<?php

namespace App\Containers\AppSection\Game\Tests\Unit;

use App\Containers\AppSection\Game\Actions\Entity\World\DndWorldAdapter;
use App\Containers\AppSection\Game\Data\Factories\GameFactory;
use App\Containers\AppSection\Game\Enum\GameStatusEnum;
use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Tests\TestCase;
use App\Containers\AppSection\User\Data\Factories\UserFactory;
use App\Containers\AppSection\User\Models\User;
use DB;

class UpdateGameUnitTest extends TestCase
{
    public function test_happyPath(): void
    {
        $this->authorize();

        $authorizedGame = $this->createGame($this->userId);

        $response = $this->patch(
            route('api_user_update_game', $authorizedGame->id),
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

        $gameAfterUpdate = DB::table('games')->where('id', $authorizedGame->id)->get()->first();

        $this->assertEquals(
            json_decode(json_encode([DndWorldAdapter::FORM_TITLE => 'Test title'])),
            json_decode($gameAfterUpdate->form_settings)
        );

        $this->assertEquals(GameStatusEnum::IN_PROGRESS, $gameAfterUpdate->status);
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

    public function test_notExistsGame(): void
    {
        $gameId = 1000;

        $this->authorize();

        $response = $this->patch(
            route('api_user_update_game', $gameId),
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

        $response->assertJsonPath(
            'message',
            sprintf('No query results for model [%s] %d', Game::class, $gameId)
        );
    }

    public function test_validationFailed_wrongGameStatus(): void
    {
        $this->authorize();

        $game = $this->createGame($this->userId, GameStatusEnum::DELETED);

        $response = $this->patch(
            route('api_user_update_game', $game->id),
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

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'game' => ['Game completed'],
            ],
        ]);
    }

    public function test_error_notAnOwner(): void
    {
        $this->authorize();

        /** @var User $user */
        $user = UserFactory::new()->create();
        $user->save();

        $game = $this->createGame($user->id);

        $response = $this->patch(
            route('api_user_update_game', $game->id),
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

        $response->assertJsonPath('message', 'This action is unauthorized.');
    }

    public function test_wrongCredentials(): void
    {
        $this->makeWrongCredentialsTests(
            route('api_user_update_game', 1)
        );
    }

    private function createGame(int $authorId, string $status = GameStatusEnum::IN_PROGRESS): Game
    {
        /** @var Game $game */
        $game = GameFactory::new()->dnd()->create([
            'author_id' => $authorId,
            'status' => $status,
            'form_settings' => [
                DndWorldAdapter::FORM_TITLE => 'New world',
                DndWorldAdapter::FORM_MAX_PLAYERS_COUNT => 3,
            ],
        ]);

        $game->save();

        return $game;
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
