<?php

declare(strict_types=1);

namespace App\Application\Auth\Exceptions;

use Exception;

final class LockoutException extends Exception
{
    /** @var int */
    private $seconds;

    private function __construct(int $seconds, string $message, int $code = 0)
    {
        $this->seconds = $seconds;

        parent::__construct($message, $code);
    }

    public function tryAgainIn(): int
    {
        return $this->seconds;
    }

    public static function tooManyFailedAttempts(int $seconds): self
    {
        return new self($seconds, 'Locked out after too many failed login attempts.');
    }
}
