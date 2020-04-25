<?php

declare(strict_types=1);

namespace App\Application\Core;

interface EventListenerInterface
{
    public function handle(EventInterface $event): void;
}
