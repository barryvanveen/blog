<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandBus;

use App\Application\Core\CommandBusInterface;
use App\Domain\Core\CommandHandlerInterface;
use App\Domain\Core\CommandInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Foundation\Application;

final class LaravelCommandBus implements CommandBusInterface
{
    private $laravelDispatcher;

    private $application;

    private $handlers;

    public function __construct(Dispatcher $laravelDispatcher, Application $application)
    {
        $this->laravelDispatcher = $laravelDispatcher;

        $this->application = $application;
    }

    public function subscribe(string $commandClassName, string $handlerClassName): void
    {
        if ($this->doesNotImplementInterface($commandClassName, CommandInterface::class)) {
            throw LaravelCommandBusException::becauseCommandIsInvalid($commandClassName);
        }

        if ($this->doesNotImplementInterface($handlerClassName, CommandHandlerInterface::class)) {
            throw LaravelCommandBusException::becauseHandlerIsInvalid($handlerClassName);
        }

        $this->handlers[$commandClassName] = $handlerClassName;
    }

    private function doesNotImplementInterface(string $subject, string $interface): bool
    {
        return ! in_array($interface, class_implements($subject), true);
    }

    public function dispatch(CommandInterface $command): void
    {
        $handlerClassName = $this->getHandlerClassName($command);

        $handler = $this->application->make($handlerClassName);

        $this->laravelDispatcher->dispatchNow($command, $handler);
    }

    private function getHandlerClassName(CommandInterface $command)
    {
        $commandClassName = get_class($command);

        if (! isset($this->handlers[$commandClassName])) {
            throw LaravelCommandBusException::becauseNoHandlerWasSubscribed($commandClassName);
        }

        return $this->handlers[$commandClassName];
    }
}
