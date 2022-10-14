<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Application\Exceptions\CommandHandlerException;

abstract class BaseCommandHandler implements CommandHandlerInterface
{
    /**
     * @throws CommandHandlerException
     */
    public function handle(CommandInterface $command): void
    {
        $method = $this->getHandleMethod($command);

        if (! method_exists($this, $method)) {
            throw CommandHandlerException::handleMethodIsMissing($method);
        }

        $this->$method($command);
    }

    private function getHandleMethod(CommandInterface $command): string
    {
        $classParts = explode('\\', $command::class);

        return 'handle' . end($classParts);
    }
}
