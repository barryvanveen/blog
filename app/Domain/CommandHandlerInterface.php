<?php

namespace App\Domain;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}
