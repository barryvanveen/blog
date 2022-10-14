<?php

declare(strict_types=1);

namespace App\Application\Auth\Events;

use App\Application\Core\EventInterface;

class LockoutWasTriggered implements EventInterface
{
    public function __construct(
        private string $email,
        private string $ip,
    ) {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function ip(): string
    {
        return $this->ip;
    }
}
