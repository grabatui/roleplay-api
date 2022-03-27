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

class AddGamePlayerUnitTest extends TestCase
{
    public function test_happyPath(): void
    {
        $this->authorize();

        $game = $this->createGame($this->userId);

        /** @var User $player */
        $player = UserFactory::new()->create();
        $player->save();

        $response = $this->put(
            route('api_user_add_game_player', [
                'game' => $game->id,
                'player' => $player->id,
            ]),
            [],
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(200);

        $this->assertTrue(
            DB::table('game_players')
                ->where('game_id', $game->id)
                ->where('player_id', $player->id)
                ->exists()
        );
    }

    public function test_validationFailed_alreadyInGame(): void
    {
        $this->authorize();

        $game = $this->createGame($this->userId);

        /** @var User $player */
        $player = UserFactory::new()->create();
        $player->save();

        DB::table('game_players')->insert([
            [
                'game_id' => $game->id,
                'player_id' => $player->id,
            ]
        ]);

        $response = $this->put(
            route('api_user_add_game_player', [
                'game' => $game->id,
                'player' => $player->id,
            ]),
            [],
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'player' => ['Player already has a part in the game'],
            ],
        ]);
    }

    public function test_validationFailed_playerIsAnGameAuthor(): void
    {
        $this->authorize();

        $game = $this->createGame($this->userId);

        $response = $this->put(
            route('api_user_add_game_player', [
                'game' => $game->id,
                'player' => $this->userId,
            ]),
            [],
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'player' => ['Player is a game author'],
            ],
        ]);
    }

    public function test_validationFailed_wrongGameStatus(): void
    {
        $this->authorize();

        $game = $this->createGame($this->userId, GameStatusEnum::COMPLETED);

        /** @var User $player */
        $player = UserFactory::new()->create();
        $player->save();

        $response = $this->put(
            route('api_user_add_game_player', [
                'game' => $game->id,
                'player' => $player->id,
            ]),
            [],
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

    public function test_wrongCredentials(): void
    {
        $this->makeWrongCredentialsTests(
            route('api_user_add_game_player', [
                'game' => 0,
                'player' => 0,
            ]),
            'put'
        );
    }

    private function createGame(int $authorId, string $status = GameStatusEnum::NEW): Game
    {
        /** @var Game $game */
        $game = GameFactory::new()->dnd()->create([
            'status' => $status,
            'author_id' => $authorId,
            'form_settings' => [
                DndWorldAdapter::TITLE => 'New world',
                DndWorldAdapter::MAX_PLAYERS_COUNT => 3,
            ],
        ]);

        $game->save();

        return $game;
    }
}
