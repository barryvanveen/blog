<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Application\Core\CommandInterface;

class Login implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember,
        public string $ip,
    ) {
    }
}
