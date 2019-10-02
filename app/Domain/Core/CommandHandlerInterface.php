<?php

declare(strict_types=1);

namespace App\Domain\Core;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;
}
