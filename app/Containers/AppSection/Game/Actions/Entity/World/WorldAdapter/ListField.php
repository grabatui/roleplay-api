<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field\TypeEnum;

class ListField extends AbstractField
{
    /**
     * @var Option[]
     */
    private array $options;

    /**
     * @param string $code
     * @param Option[] $options
     * @return static
     */
    public static function make(string $code, array $options): self
    {
        $field = new self($code, TypeEnum::LIST);

        $field->setOptions($options);

        return $field;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
