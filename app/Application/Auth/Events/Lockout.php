<?php

declare(strict_types=1);

namespace App\Application\Auth\Events;

use App\Application\Core\EventInterface;

class Lockout implements EventInterface
{
    /** @var string */
    public $username;

    /** @var string */
    public $ip;

    public function __construct(string $username, string $ip)
    {
        $this->username = $username;

        $this->ip = $ip;
    }
}
