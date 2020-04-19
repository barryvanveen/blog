<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\GuardInterface;
use App\Domain\Users\Models\User;
use App\Infrastructure\Eloquent\UserEloquentModel;
use App\Infrastructure\Exceptions\InvalidGuardException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\UnauthorizedException;

class LaravelGuard implements GuardInterface
{
    /** @var StatefulGuard&Guard */
    private $laravelGuard;

    public function __construct(Factory $authFactory)
    {
        $this->laravelGuard = $authFactory->guard();

        if (($this->laravelGuard instanceof StatefulGuard) === false ||
            ($this->laravelGuard instanceof Guard) === false) {
            throw InvalidGuardException::becauseGuardDoesNotExtendTheCorrectInterfaces(get_class($this->laravelGuard));
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

    public function user(): User
    {
        /** @var UserEloquentModel|null $laravelUser */
        $laravelUser = $this->laravelGuard->user();

        if ($laravelUser === null) {
            throw new UnauthorizedException();
        }

        return new User(
            $laravelUser->email,
            $laravelUser->name,
            $laravelUser->password,
            $laravelUser->remember_token,
            $laravelUser->uuid
        );
    }
}
