<?php

namespace App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapter\Field\TypeEnum;

class Field extends AbstractField
{
    public static function make(string $code, TypeEnum $type): self
    {
        return new static($code, $type);
    }
}
