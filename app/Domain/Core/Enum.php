<?php

declare(strict_types=1);

namespace App\Domain\Core;

abstract class Enum
{
    /** @var int */
    protected $value;

    public function __construct(int $value)
    {
        $this->value = $value;
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
