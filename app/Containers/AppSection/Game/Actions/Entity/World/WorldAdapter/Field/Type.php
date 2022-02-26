<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field;

use InvalidArgumentException;

class Type
{
    public const STRING = 'string';
    public const INTEGER = 'integer';

    private const ALL = [
        self::STRING,
        self::INTEGER,
    ];

    private string $value;

    public function __construct(string $value)
    {
        if (! in_array($value, static::ALL)) {
            throw new InvalidArgumentException('Wrong type ' . $value);
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
