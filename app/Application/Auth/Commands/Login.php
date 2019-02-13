<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Domain\Core\CommandInterface;

class Login implements CommandInterface
{
    public $email;

    public $password;

    public $remember;

    public $ip;

    public function __construct(string $email, string $password, bool $remember, string $ip)
    {
        $this->email = $email;

        $this->password = $password;

        $this->remember = $remember;

        $this->ip = $ip;
    }
}
