<?php

namespace App\Infrastructure\CommandBus;

use App\Application\Core\CommandBusInterface;
use App\Domain\Core\CommandInterface;
use Illuminate\Bus\Dispatcher;
use Illuminate\Foundation\Application;

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

    public function subscribe(string $commandClassName, string $handlerClassName)
    {
        $this->handlers[$commandClassName] = $handlerClassName;
    }

    public function dispatch(CommandInterface $command)
    {
        $handlerClassName = $this->getHandlerClassName($command);

        $handler = $this->application->make($handlerClassName);

        $this->laravelDispatcher->dispatchNow($command, $handler);
    }

    private function getHandlerClassName(CommandInterface $command)
    {
        $commandClassName = get_class($command);

        if (! isset($this->handlers[$commandClassName])) {
            throw new \Exception('No handler found for '.$commandClassName);
        }

        // todo: check if handler implements CommandHandlerInterface

        return $this->handlers[$commandClassName];
    }
}
