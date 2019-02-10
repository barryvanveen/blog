<?php

namespace App\Application;

use App\Domain\CommandInterface;

interface CommandBusInterface
{
    public function subscribe(string $commandClassName, string $handlerClassName);

    public function dispatch(CommandInterface $command);
}
