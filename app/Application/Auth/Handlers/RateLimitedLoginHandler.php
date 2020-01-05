<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Events\Lockout;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Auth\Exceptions\LockoutException;
use App\Application\Core\BaseCommandHandler;
use App\Application\Interfaces\RateLimiterInterface;
use App\Domain\Core\CommandHandlerInterface;

final class RateLimitedLoginHandler extends BaseCommandHandler
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_IN_SECONDS = 60;

    /** @var CommandHandlerInterface */
    private $loginHandler;

    /** @var \App\Application\Interfaces\RateLimiterInterface */
    private $limiter;

    public function __construct(
        CommandHandlerInterface $loginHandler,
        RateLimiterInterface $limiter
    ) {
        $this->loginHandler = $loginHandler;

        $this->limiter = $limiter;
    }

    /**
     * @param Login $command
     *
     * @return void
     *
     * @throws LockoutException
     * @throws FailedLoginException
     */
    public function handleLogin(Login $command): void
    {
        if ($this->hasTooManyLoginAttempts($command)) {
            event(new Lockout($command->email, $command->ip));

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
        return mb_strtolower($command->email, 'UTF-8').'|'.$command->ip;
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
