<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Domain\Core\CommandHandlerInterface;
use App\Domain\Core\CommandInterface;

abstract class BaseCommandHandler implements CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     *
     * @throws CommandHandlerException
     */
    public function handle(CommandInterface $command)
    {
        $method = $this->getHandleMethod($command);

        if (! method_exists($this, $method)) {
            throw CommandHandlerException::handleMethodIsMissing($method);
        }

        $this->$method($command);
    }

    private function getHandleMethod(CommandInterface $command): string
    {
        $classParts = explode('\\', get_class($command));

        return 'handle'.end($classParts);
    }
}
