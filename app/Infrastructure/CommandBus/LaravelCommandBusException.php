<?php

namespace App\Infrastructure\CommandBus;

use App\Domain\Core\CommandHandlerInterface;
use App\Domain\Core\CommandInterface;
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
