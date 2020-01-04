<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\GuardInterface;
use App\Infrastructure\Exceptions\InvalidGuardException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\StatefulGuard;

class LaravelGuard implements GuardInterface
{
    /** @var StatefulGuard */
    private $laravelGuard;

    public function __construct(Factory $authFactory)
    {
        $this->laravelGuard = $authFactory->guard();

        if (($this->laravelGuard instanceof StatefulGuard) === false) {
            throw InvalidGuardException::becauseStatefulGaurdIsNeeded(get_class($this->laravelGuard));
        }
    }

    public function attempt(string $email, string $password): bool
    {
        return $this->laravelGuard->attempt([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function logout(): void
    {
        $this->laravelGuard->logout();
    }
}
