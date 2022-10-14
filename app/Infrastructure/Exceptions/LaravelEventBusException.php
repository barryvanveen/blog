<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Application\Core\EventInterface;
use App\Application\Core\EventListenerInterface;
use LogicException;

class LaravelEventBusException extends LogicException
{
    public static function becauseEventIsInvalid(string $eventClassName): self
    {
        return new self('Event ' . $eventClassName . ' does not implement ' . EventInterface::class);
    }

    public static function becauseListenerIsInvalid(string $listenerClassName): self
    {
        return new self('Listener ' . $listenerClassName . ' does not implement ' . EventListenerInterface::class);
    }
}
