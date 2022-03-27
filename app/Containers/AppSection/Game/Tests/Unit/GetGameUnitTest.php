<?php

namespace App\Containers\AppSection\Game\Tests\Unit;

use App\Containers\AppSection\Game\Actions\Entity\World\DndWorldAdapter;
use App\Containers\AppSection\Game\Data\Factories\GameFactory;
use App\Containers\AppSection\Game\Models\Game;
use App\Containers\AppSection\Game\Tests\TestCase;
use App\Containers\AppSection\User\Data\Factories\UserFactory;
use App\Containers\AppSection\User\Models\User;
use DB;

class GetGameUnitTest extends TestCase
{
    public function test_happyPath(): void
    {
        $this->authorize();

        $authorizedGame = $this->createGame($this->userId);

        $response = $this->get(
            route('api_user_get_game', ['game' => $authorizedGame->id]),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonPath('data.id', $authorizedGame->id);
        $response->assertJsonPath('data.status', $authorizedGame->status);
        $response->assertJsonPath('data.author.id', $authorizedGame->author->getHashedKey());
    }

    public function test_happyPath_withPlayers(): void
    {
        $this->authorize();

        /** @var User $player1 */
        $player1 = UserFactory::new()->create();
        $player1->save();

        /** @var User $player2 */
        $player2 = UserFactory::new()->create();
        $player2->save();

        $authorizedGame = $this->createGame($this->userId);

        DB::table('game_players')->insert([
            [
                'game_id' => $authorizedGame->id,
                'player_id' => $player1->id,
            ],
            [
                'game_id' => $authorizedGame->id,
                'player_id' => $player2->id,
            ],
        ]);

        $response = $this->get(
            route('api_user_get_game', ['game' => $authorizedGame->id]),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonPath('data.id', $authorizedGame->id);
        $response->assertJsonPath('data.status', $authorizedGame->status);
        $response->assertJsonPath('data.author.id', $authorizedGame->author->getHashedKey());

        $response->assertJsonCount(2, 'data.players');
    }

    public function test_notAuthorizedGame(): void
    {
        $this->authorize();

        /** @var User $user */
        $user = UserFactory::new()->create();

        $user->save();

        $notAuthorizedGame = $this->createGame($user->id);

        $response = $this->get(
            route('api_user_get_game', ['game' => $notAuthorizedGame->id]),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonPath('message', 'This action is unauthorized.');
    }

    public function test_notExistsGame(): void
    {
        $gameId = 1000;

        $this->authorize();

        $response = $this->get(
            route('api_user_get_game', ['game' => $gameId]),
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

    public function test_wrongCredentials(): void
    {
        $this->makeWrongCredentialsTests(
            route('api_user_get_game', ['game' => 0])
        );
    }

    private function createGame(int $authorId): Game
    {
        /** @var Game $game */
        $game = GameFactory::new()->dnd()->create([
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
