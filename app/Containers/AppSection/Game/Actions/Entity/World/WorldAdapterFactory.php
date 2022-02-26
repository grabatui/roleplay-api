<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World;

use App\Ship\Exceptions\NotFoundException;

class WorldAdapterFactory
{
    /**
     * @var string[]|WorldAdapterInterface[]
     */
    private static array $adapters = [
        DndWorldAdapter::class,
    ];

    /**
     * @return string[]
     */
    public static function getCodes(): array
    {
        return array_map(
            static fn(string $worldAdapter): string => $worldAdapter::getCode(),
            static::$adapters
        );
    }

    public static function getByCode(string $code): WorldAdapterInterface
    {
        foreach (static::$adapters as $adapter) {
            if ($adapter::getCode() === $code) {
                return new $adapter();
            }
        }

        throw new NotFoundException('Adapter not found for ' . $code);
    }

    public function getAll(): array
    {
        return array_map(
            static fn(string $adapterClass): WorldAdapterInterface => new $adapterClass(),
            static::$adapters
        );
    }
}
