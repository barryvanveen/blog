<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Core\CommandHandlerInterface;
use App\Application\Core\CommandInterface;
use App\Application\Interfaces\CommandBusInterface;
use App\Infrastructure\Exceptions\LaravelCommandBusException;
use Illuminate\Contracts\Bus\Dispatcher;

final class LaravelCommandBus implements CommandBusInterface
{
    public function __construct(private Dispatcher $laravelDispatcher)
    {
    }

    public function subscribe(string $commandClassName, string $handlerClassName): void
    {
        if ($this->doesNotImplementInterface($commandClassName, CommandInterface::class)) {
            throw LaravelCommandBusException::becauseCommandIsInvalid($commandClassName);
        }

        if ($this->doesNotImplementInterface($handlerClassName, CommandHandlerInterface::class)) {
            throw LaravelCommandBusException::becauseHandlerIsInvalid($handlerClassName);
        }

        $this->laravelDispatcher->map([$commandClassName => $handlerClassName]);
    }

    private function doesNotImplementInterface(string $subject, string $interface): bool
    {
        return ! in_array($interface, class_implements($subject), true);
    }

    public function dispatch(CommandInterface $command): void
    {
        if ($this->laravelDispatcher->hasCommandHandler($command) === false) {
            throw LaravelCommandBusException::becauseNoHandlerWasSubscribed($command::class);
        }

        $this->laravelDispatcher->dispatch($command);
    }
}
