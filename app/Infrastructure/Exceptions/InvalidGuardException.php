<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Illuminate\Contracts\Auth\StatefulGuard;
use RuntimeException;

final class InvalidGuardException extends RuntimeException
{
    public static function becauseStatefulGaurdIsNeeded(string $currentGuardClassname): self
    {
        return new self('Guard '.$currentGuardClassname.' is not an instance of '.StatefulGuard::class);
    }
}
