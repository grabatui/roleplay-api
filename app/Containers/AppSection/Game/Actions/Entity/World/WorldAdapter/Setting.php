<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field\Type;
use JetBrains\PhpStorm\Pure;

class Setting
{
    private bool $isRequired = false;
    private bool $isHintExists = false;

    public function __construct(
        private string $code,
        private Type $type
    ) {
    }

    #[Pure]
    public static function make(string $code, Type $type): self
    {
        return new static($code, $type);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): Type
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
