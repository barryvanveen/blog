<?php

declare(strict_types=1);

namespace App\Application\Core;

use BadMethodCallException;

class EventListenerException extends BadMethodCallException
{
    public static function handleMethodIsMissing(string $handleMethodName): self
    {
        return new self('EventListener should include method '.$handleMethodName);
    }
}
