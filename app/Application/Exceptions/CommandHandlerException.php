<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use BadMethodCallException;

class CommandHandlerException extends BadMethodCallException
{
    public static function handleMethodIsMissing(string $handleMethodName): self
    {
        return new self('CommandHandler should include method '.$handleMethodName);
    }
}
