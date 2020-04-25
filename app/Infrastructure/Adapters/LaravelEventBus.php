<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Core\EventBusInterface;
use App\Application\Core\EventInterface;
use App\Application\Core\EventListenerInterface;
use App\Infrastructure\Exceptions\LaravelEventBusException;
use Illuminate\Contracts\Events\Dispatcher;

final class LaravelEventBus implements EventBusInterface
{
    /** @var Dispatcher */
    private $laravelDispatcher;

    public function __construct(Dispatcher $laravelDispatcher)
    {
        $this->laravelDispatcher = $laravelDispatcher;
    }

    public function subscribe(string $commandClassName, string $listenerClassName): void
    {
        if ($this->doesNotImplementInterface($commandClassName, EventInterface::class)) {
            throw LaravelEventBusException::becauseEventIsInvalid($commandClassName);
        }

        if ($this->doesNotImplementInterface($listenerClassName, EventListenerInterface::class)) {
            throw LaravelEventBusException::becauseListenerIsInvalid($listenerClassName);
        }

        $this->laravelDispatcher->listen($commandClassName, $listenerClassName);
    }

    private function doesNotImplementInterface(string $subject, string $interface): bool
    {
        return ! in_array($interface, class_implements($subject), true);
    }

    public function dispatch(EventInterface $command): void
    {
        $this->laravelDispatcher->dispatch($command);
    }
}
