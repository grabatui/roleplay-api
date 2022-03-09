<?php

namespace App\Containers\AppSection\Game\Data\Factories;

use App\Containers\AppSection\Game\Actions\Entity\World\DndWorldAdapter;
use App\Containers\AppSection\Game\Enum\UserWorldStatusEnum;
use App\Containers\AppSection\Game\Models\UserWorld;
use App\Ship\Parents\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class UserWorldFactory extends Factory
{
    protected $model = UserWorld::class;

    #[ArrayShape([
        'status' => "string",
        'created_at' => "\Illuminate\Support\Carbon",
        'updated_at' => "\Illuminate\Support\Carbon"
    ])]
    public function definition(): array
    {
        return [
            'status' => UserWorldStatusEnum::NEW,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function dnd(): self
    {
        return $this->state(
            fn(array $attributes): array => [
                'world_code' => DndWorldAdapter::getCode(),
            ]
        );
    }
}
