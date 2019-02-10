<?php

namespace App\Application\Core;

class CommandHandlerException extends \BadMethodCallException
{
    public static function handleMethodIsMissing(string $handleMethodName): self
    {
        return new self('CommandHandler should include method '.$handleMethodName);
    }
}
