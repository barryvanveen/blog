<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Core\BaseCommandHandler;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class LoginHandler extends BaseCommandHandler
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_MINUTES = 1;

    /** @var RateLimiter */
    private $limiter;

    /** @var Store */
    private $session;

    public function __construct(RateLimiter $limiter, Store $session)
    {
        $this->limiter = $limiter;

        $this->session = $session;
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

        if ($this->attemptLogin($command)) {
            $this->login($command);
            return;
        }

        $this->incrementLoginAttempts($command);

        throw $this->getFailedException();
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

    private function attemptLogin(Login $command): bool
    {
        return Auth::guard()->attempt(
            [
                'email' => $command->email,
                'password' => $command->password,
            ],
            $command->remember
        );
    }

    private function login(Login $command): void
    {
        $this->session->regenerate();

        $this->clearLoginAttempts($command);
    }

    private function clearLoginAttempts(Login $command): void
    {
        $this->limiter->clear($this->throttleKey($command));
    }

    private function incrementLoginAttempts(Login $command): void
    {
        $this->limiter->hit($this->throttleKey($command), self::DECAY_MINUTES);
    }

    private function getFailedException(): ValidationException
    {
        return ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
