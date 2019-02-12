<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Domain\Core\CommandInterface;

interface CommandBusInterface
{
    public function subscribe(string $commandClassName, string $handlerClassName);

    public function dispatch(CommandInterface $command);
}
