<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\GuardInterface;
use App\Domain\Users\Models\User;
use App\Infrastructure\Eloquent\UserEloquentModel;
use App\Infrastructure\Exceptions\InvalidGuardException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\UnauthorizedException;

class LaravelGuard implements GuardInterface
{
    private StatefulGuard $laravelGuard;

    public function __construct(
        Factory $authFactory,
    ) {
        $guard = $authFactory->guard();

        if (($guard instanceof StatefulGuard) === false) {
            throw InvalidGuardException::becauseGuardDoesNotExtendTheCorrectInterfaces($guard::class);
        }

        $this->laravelGuard = $guard;
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

    public function authenticated(): bool
    {
        return $this->laravelGuard->user() !== null;
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
