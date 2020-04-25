<?php

declare(strict_types=1);

namespace App\Application\Core;

interface EventBusInterface
{
    public function subscribe(string $eventClassName, string $listenerClassName): void;

    public function dispatch(EventInterface $event): void;
}
