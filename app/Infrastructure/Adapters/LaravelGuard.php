<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\GuardInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\StatefulGuard;

class LaravelGuard implements GuardInterface
{
    /** @var StatefulGuard */
    private $laravelGuard;

    public function __construct(AuthManager $authManager)
    {
        $this->laravelGuard = $authManager->guard();
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array $credentials
     * @param  bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false): bool
    {
        return $this->laravelGuard->attempt($credentials, $remember);
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array $credentials
     * @return bool
     */
    public function once(array $credentials = []): bool
    {
        return $this->laravelGuard->once($credentials);
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember(): bool
    {
        return $this->laravelGuard->viaRemember();
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->laravelGuard->logout();
    }
}
