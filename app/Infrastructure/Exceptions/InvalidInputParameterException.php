<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use RuntimeException;

final class InvalidInputParameterException extends RuntimeException
{
    public static function becauseParameterShouldBeAString(string $parameter): self
    {
        return new self('Parameter '.$parameter.' should be a string');
    }

    public static function becauseParameterShouldBeAnInteger(string $parameter): self
    {
        return new self('Parameter '.$parameter.' should be an integer');
    }
}
