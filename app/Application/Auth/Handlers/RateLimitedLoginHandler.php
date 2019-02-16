<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\RateLimiterInterface;
use App\Application\Core\BaseCommandHandler;
use App\Domain\Core\CommandHandlerInterface;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class RateLimitedLoginHandler extends BaseCommandHandler
{
    // todo: these should be config values
    private const MAX_ATTEMPTS = 5;

    private const DECAY_MINUTES = 1;

    /** @var CommandHandlerInterface */
    private $loginHandler;

    /** @var RateLimiterInterface */
    private $limiter;

    public function __construct(CommandHandlerInterface $loginHandler, RateLimiterInterface $limiter)
    {
        $this->loginHandler = $loginHandler;

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
            $this->loginHandler->handle($command);
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
