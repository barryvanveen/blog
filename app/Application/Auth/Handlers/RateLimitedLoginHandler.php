<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Auth\Exceptions\LockoutException;
use App\Application\Core\BaseCommandHandler;
use App\Application\Core\CommandHandlerInterface;
use App\Application\Interfaces\RateLimiterInterface;

final class RateLimitedLoginHandler extends BaseCommandHandler
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_IN_SECONDS = 60;

    public function __construct(
        private CommandHandlerInterface $loginHandler,
        private RateLimiterInterface $limiter,
    ) {
    }

    /**
     *
     *
     * @throws LockoutException
     * @throws FailedLoginException
     */
    public function handleLogin(Login $command): void
    {
        if ($this->hasTooManyLoginAttempts($command)) {
            throw $this->getLockoutException($command);
        }

        try {
            $this->loginHandler->handle($command);
        } catch (FailedLoginException $e) {
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
        return mb_strtolower($command->email, 'UTF-8') . '|' . $command->ip;
    }

    private function getLockoutException(Login $login): LockoutException
    {
        $seconds = $this->limiter->availableIn(
            $this->throttleKey($login)
        );

        return LockoutException::tooManyFailedAttempts($seconds);
    }

    private function incrementLoginAttempts(Login $command): void
    {
        $this->limiter->hit($this->throttleKey($command), self::DECAY_IN_SECONDS);
    }

    private function clearLoginAttempts(Login $command): void
    {
        $this->limiter->clear($this->throttleKey($command));
    }
}
