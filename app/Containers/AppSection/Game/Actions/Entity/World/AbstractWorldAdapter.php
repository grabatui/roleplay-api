<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field;
use App\Containers\AppSection\Game\Enum\GameValidationTypeEnum;
use App\Containers\AppSection\Game\Exceptions\GameValidationFailedException;
use Arr;

abstract class AbstractWorldAdapter implements WorldAdapterInterface
{
    public function getFormFieldByCode(string $code): ?Field
    {
        return Arr::first(
            $this->getFormFields(),
            static fn(Field $field): bool => $field->getCode() === $code
        );
    }

    public function validateField(Field $field, mixed $value): void
    {
        if ($field->isRequired() && !$value) {
            throw new GameValidationFailedException(
                $field->getCode(),
                GameValidationTypeEnum::required
            );
        }
    }

    public function hasRequiredFormFields(): bool
    {
        return !is_null(
            Arr::first(
                $this->getFormFields(),
                static fn(Field $field): bool => $field->isRequired()
            )
        );
    }
}
