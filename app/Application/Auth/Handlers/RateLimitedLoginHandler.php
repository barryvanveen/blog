<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class RateLimitedLoginHandler extends LoginHandler
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_MINUTES = 1;

    /** @var RateLimiter */
    private $limiter;

    public function __construct(RateLimiter $limiter, Store $session)
    {
        parent::__construct($session);

        $this->limiter = $limiter;
    }

    /**
     * @param Login $command
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function handleLogin(Login $command): void
    {
        if ($this->hasTooManyLoginAttempts($command)) {
            $this->fireLockoutEvent();

            throw $this->getLockoutException($command);
        }

        try {
            parent::handleLogin($command);
        } catch (ValidationException $e) {
            $this->incrementLoginAttempts($command);

            throw $e;
        }

        $this->clearLoginAttempts($command);
    }

    private function hasTooManyLoginAttempts(Login $command): bool
    {
        return $this->limiter->tooManyAttempts($this->throttleKey($command), self::MAX_ATTEMPTS);
    }

    private function throttleKey(Login $command): string
    {
        return Str::lower($command->email).'|'.$command->ip;
    }

    private function fireLockoutEvent(): void
    {
        $request = app()->make(Request::class);

        event(new Lockout($request));
    }

    private function getLockoutException(Login $login): ValidationException
    {
        $seconds = $this->limiter->availableIn(
            $this->throttleKey($login)
        );

        return ValidationException::withMessages([
            'email' => [Lang::get('auth.throttle', ['seconds' => $seconds])],
        ])->status(429);
    }

    private function incrementLoginAttempts(Login $command): void
    {
        $this->limiter->hit($this->throttleKey($command), self::DECAY_MINUTES);
    }

    private function clearLoginAttempts(Login $command): void
    {
        $this->limiter->clear($this->throttleKey($command));
    }
}
