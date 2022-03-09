<?php

namespace App\Containers\AppSection\Game\Tests\Unit;

use App\Containers\AppSection\Game\Actions\Entity\World\DndWorldAdapter;
use App\Containers\AppSection\Game\Data\Factories\UserWorldFactory;
use App\Containers\AppSection\Game\Models\UserWorld;
use App\Containers\AppSection\Game\Tests\TestCase;
use App\Containers\AppSection\User\Data\Factories\UserFactory;
use App\Containers\AppSection\User\Models\User;
use Hash;

class GetUserWorldUnitTest extends TestCase
{
    public function test_happyPath(): void
    {
        $this->authorize();

        $authorizedUserWorld = $this->createUserWorld($this->userId);

        $response = $this->get(
            route('api_user_get_user_world', ['userWorld' => $authorizedUserWorld->id]),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonPath('data.id', $authorizedUserWorld->id);
        $response->assertJsonPath('data.status', $authorizedUserWorld->status);
        $response->assertJsonPath('data.author_id', $authorizedUserWorld->author->getHashedKey());
    }

    public function test_notAuthorizedUserWorld(): void
    {
        $this->authorize();

        /** @var User $user */
        $user = UserFactory::new()->create([
            'email' => 'test2@test.test',
            'password' => Hash::make('testPassword'),
        ]);

        $user->save();

        $notAuthorizedUserWorld = $this->createUserWorld($user->id);

        $response = $this->get(
            route('api_user_get_user_world', ['userWorld' => $notAuthorizedUserWorld->id]),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonPath('message', 'This action is unauthorized.');
    }

    public function test_notExistsUserWorld(): void
    {
        $userWorldId = 1000;

        $this->authorize();

        $response = $this->get(
            route('api_user_get_user_world', ['userWorld' => $userWorldId]),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $response->assertJsonPath(
            'message',
            sprintf('No query results for model [%s] %d', UserWorld::class, $userWorldId)
        );
    }

    public function test_wrongCredentials(): void
    {
        // User is not authorized
        $response = $this->get(
            route('api_user_get_user_world', ['userWorld' => 0]),
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
            route('api_user_get_user_world', ['userWorld' => 0]),
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

    private function createUserWorld(int $authorId): UserWorld
    {
        /** @var UserWorld $userWorld */
        $userWorld = UserWorldFactory::new()->dnd()->create([
            'author_id' => $authorId,
            'form_settings' => [
                DndWorldAdapter::TITLE => 'New world',
                DndWorldAdapter::MAX_PLAYERS_COUNT => 3,
            ],
        ]);

        $userWorld->save();

        return $userWorld;
    }
}
