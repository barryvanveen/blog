<?php

declare(strict_types=1);

namespace App\Domain\Core;

use Stringable;

abstract class Enum implements Stringable
{
    public function __construct(
        protected int $value,
    ) {
    }

    public function equals(self $value): bool
    {
        return $value->getValue() === $this->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
