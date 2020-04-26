<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Application\Core\CommandHandlerInterface;
use App\Application\Core\CommandInterface;
use LogicException;

class LaravelCommandBusException extends LogicException
{
    public static function becauseNoHandlerWasSubscribed(string $commandClassName): self
    {
        return new self('No handler found for '.$commandClassName);
    }

    public static function becauseCommandIsInvalid(string $commandClassName): self
    {
        return new self('Command '.$commandClassName.' does not implement '.CommandInterface::class);
    }

    public static function becauseHandlerIsInvalid(string $handlerClassName): self
    {
        return new self('Handler '.$handlerClassName.' does not implement '.CommandHandlerInterface::class);
    }
}
