<?php

namespace App\Containers\AppSection\Game\Tests\Unit;

use App\Containers\AppSection\Game\Actions\Entity\World\DndWorldAdapter;
use App\Containers\AppSection\Game\Enum\GameStatusEnum;
use App\Containers\AppSection\Game\Tests\TestCase;
use Carbon\Carbon;
use DB;
use Illuminate\Testing\AssertableJsonString;

class GetWorldsUnitTest extends TestCase
{
    public function test_happyPath_withoutExists(): void
    {
        $this->authorize();

        $response = $this->get(
            route('api_user_get_games'),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $itemsByCode = $this->makeItemsByCode(
            $response->decodeResponseJson()
        );

        $this->assertNotEmpty($itemsByCode);

        $this->assertDndWorld(
            $itemsByCode['dnd'] ?? []
        );
    }

    public function test_happyPath_withExists(): void
    {
        $newGameFormSettings = [
            DndWorldAdapter::TITLE => 'New world',
            DndWorldAdapter::MAX_PLAYERS_COUNT => 3,
        ];
        $newGame = [
            'world_code' => 'dnd',
            'author_id' => $this->userId,
            'status' => GameStatusEnum::NEW,
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
            'form_settings' => json_encode($newGameFormSettings),
        ];

        $inProgressGameFormSettings = [
            DndWorldAdapter::TITLE => 'World in progress',
            DndWorldAdapter::MAX_PLAYERS_COUNT => 2,
        ];
        $inProgressGame = [
            'world_code' => 'dnd',
            'author_id' => $this->userId,
            'status' => GameStatusEnum::IN_PROGRESS,
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
            'form_settings' => json_encode($inProgressGameFormSettings),
        ];

        $deletedGameFormSettings = [
            DndWorldAdapter::TITLE => 'Deleted world',
            DndWorldAdapter::MAX_PLAYERS_COUNT => 4,
        ];
        $deletedGame = [
            'world_code' => 'dnd',
            'author_id' => $this->userId,
            'status' => GameStatusEnum::DELETED,
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
            'form_settings' => json_encode($deletedGameFormSettings),
        ];

        DB::table('games')->insert([
            $newGame,
            $inProgressGame,
            $deletedGame,
        ]);

        $this->authorize();

        $response = $this->get(
            route('api_user_get_games'),
            array_merge(
                $this->getApiHeaders($this->accessToken),
                ['Accept-Language' => 'en']
            )
        );

        $itemsByCode = $this->makeItemsByCode(
            $response->decodeResponseJson()
        );

        $this->assertNotEmpty($itemsByCode);

        $this->assertDndWorld(
            $itemsByCode['dnd'] ?? []
        );

        $this->assertNotEmpty(
            $itemsByCode['dnd']['games'] ?? []
        );

        $this->assertEquals($newGame['status'], $itemsByCode['dnd']['games'][0]['status']);
        $this->assertEquals($inProgressGame['status'], $itemsByCode['dnd']['games'][1]['status']);
        $this->assertEquals($deletedGame['status'], $itemsByCode['dnd']['games'][2]['status']);

        $this->assertEquals($newGameFormSettings, $itemsByCode['dnd']['games'][0]['form_settings']);
        $this->assertEquals($inProgressGameFormSettings, $itemsByCode['dnd']['games'][1]['form_settings']);
        $this->assertEquals($deletedGameFormSettings, $itemsByCode['dnd']['games'][2]['form_settings']);
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

    private function makeItemsByCode(AssertableJsonString $response): array
    {
        $result = [];
        foreach ($response['data'] as $world) {
            $result[$world['code']] = $world;
        }

        return $result;
    }

    private function assertDndWorld(array $world): void
    {
        $this->assertEquals('dnd', $world['code'] ?? null);

        $formSettingsCodes = array_map(
            static fn(array $formSetting): string => $formSetting['code'] ?? '',
            $world['form_settings'] ?? []
        );

        sort($formSettingsCodes);

        $expectedFormSettings = array_keys(
            (new DndWorldAdapter())->getSettings()
        );

        sort($expectedFormSettings);

        $this->assertEquals(
            implode('-', $expectedFormSettings),
            implode('-', $formSettingsCodes)
        );
    }
}
