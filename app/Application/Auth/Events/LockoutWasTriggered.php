<?php

declare(strict_types=1);

namespace App\Application\Auth\Events;

use App\Application\Core\EventInterface;

class LockoutWasTriggered implements EventInterface
{
    /** @var string */
    private $email;

    /** @var string */
    private $ip;

    public function __construct(
        string $email,
        string $ip
    ) {
        $this->email = $email;
        $this->ip = $ip;
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
