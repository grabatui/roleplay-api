<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field\TypeEnum;

abstract class AbstractField
{
    private bool $isRequired = false;
    private bool $isHintExists = false;

    protected function __construct(
        private readonly string $code,
        private readonly TypeEnum $type
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): TypeEnum
    {
        return $this->type;
    }

    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function setIsHintExists(bool $isHintExists): self
    {
        $this->isHintExists = $isHintExists;

        return $this;
    }

    public function isHintExists(): bool
    {
        return $this->isHintExists;
    }
}
