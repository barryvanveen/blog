<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Application\Core\EventInterface;

interface EventBusInterface
{
    public function subscribe(string $eventClassName, string $listenerClassName): void;

    public function dispatch(EventInterface $event): void;
}
