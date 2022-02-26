<?php

namespace App\Containers\AppSection\Game\Tests\Unit;

use App\Containers\AppSection\Game\Actions\Entity\World\DndWorldAdapter;
use App\Containers\AppSection\Game\Enum\UserWorldStatusEnum;
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
            route('api_user_get_user_worlds'),
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
        $newUserWorldFormSettings = [
            DndWorldAdapter::TITLE => 'New world',
            DndWorldAdapter::MAX_PLAYERS_COUNT => 3,
        ];
        $newUserWorld = [
            'world_code' => 'dnd',
            'author_id' => $this->userId,
            'status' => UserWorldStatusEnum::NEW,
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
            'form_settings' => json_encode($newUserWorldFormSettings),
        ];

        $inProgressUserWorldFormSettings = [
            DndWorldAdapter::TITLE => 'World in progress',
            DndWorldAdapter::MAX_PLAYERS_COUNT => 2,
        ];
        $inProgressUserWorld = [
            'world_code' => 'dnd',
            'author_id' => $this->userId,
            'status' => UserWorldStatusEnum::IN_PROGRESS,
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
            'form_settings' => json_encode($inProgressUserWorldFormSettings),
        ];

        $deletedUserWorldFormSettings = [
            DndWorldAdapter::TITLE => 'Deleted world',
            DndWorldAdapter::MAX_PLAYERS_COUNT => 4,
        ];
        $deletedUserWorld = [
            'world_code' => 'dnd',
            'author_id' => $this->userId,
            'status' => UserWorldStatusEnum::DELETED,
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
            'form_settings' => json_encode($deletedUserWorldFormSettings),
        ];

        DB::table('user_worlds')->insert([
            $newUserWorld,
            $inProgressUserWorld,
            $deletedUserWorld,
        ]);

        $this->authorize();

        $response = $this->get(
            route('api_user_get_user_worlds'),
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
            $itemsByCode['dnd']['user_worlds'] ?? []
        );

        $this->assertEquals($newUserWorld['status'], $itemsByCode['dnd']['user_worlds'][0]['status']);
        $this->assertEquals($inProgressUserWorld['status'], $itemsByCode['dnd']['user_worlds'][1]['status']);
        $this->assertEquals($deletedUserWorld['status'], $itemsByCode['dnd']['user_worlds'][2]['status']);

        $this->assertEquals($newUserWorldFormSettings, $itemsByCode['dnd']['user_worlds'][0]['form_settings']);
        $this->assertEquals($inProgressUserWorldFormSettings, $itemsByCode['dnd']['user_worlds'][1]['form_settings']);
        $this->assertEquals($deletedUserWorldFormSettings, $itemsByCode['dnd']['user_worlds'][2]['form_settings']);
    }

    public function test_wrongCredentials(): void
    {
        // User is not authorized
        $response = $this->get(
            route('api_user_get_user_worlds'),
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
            route('api_user_get_user_worlds'),
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
