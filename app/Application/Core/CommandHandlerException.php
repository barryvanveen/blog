<?php

declare(strict_types=1);

namespace App\Application\Core;

class CommandHandlerException extends \BadMethodCallException
{
    public static function handleMethodIsMissing(string $handleMethodName): self
    {
        return new self('CommandHandler should include method '.$handleMethodName);
    }
}
