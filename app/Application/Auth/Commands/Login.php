<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Application\Core\CommandInterface;

class Login implements CommandInterface
{
    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var bool */
    public $remember;

    /** @var string */
    public $ip;

    public function __construct(string $email, string $password, bool $remember, string $ip)
    {
        $this->email = $email;

        $this->password = $password;

        $this->remember = $remember;

        $this->ip = $ip;
    }
}
