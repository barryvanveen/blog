<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use RuntimeException;

final class InvalidRouteParameterException extends RuntimeException
{
    public static function becauseParameterShouldBeAString(string $parameter): self
    {
        return new self('Parameter '.$parameter.' should be a string');
    }
}
