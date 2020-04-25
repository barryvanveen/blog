<?php

declare(strict_types=1);

namespace App\Application\Core;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;
}
