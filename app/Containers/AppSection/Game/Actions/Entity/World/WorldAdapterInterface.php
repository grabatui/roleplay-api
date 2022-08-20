<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field;
use App\Containers\AppSection\Game\Exceptions\GameValidationFailedException;

interface WorldAdapterInterface
{
    public static function getCode(): string;

    /**
     * @return array<string, Field>
     */
    public function getFormFields(): array;

    public function getFormFieldByCode(string $code): ?Field;

    public function hasRequiredFormFields(): bool;

    /**
     * @return array<string, Field>
     */
    public function getCharacterFields(): array;

    /**
     * @param Field $field
     * @param mixed $value
     * @return void
     * @throws GameValidationFailedException
     */
    public function validateField(Field $field, mixed $value): void;
}
