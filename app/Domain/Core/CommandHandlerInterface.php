<?php

namespace App\Domain\Core;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}
