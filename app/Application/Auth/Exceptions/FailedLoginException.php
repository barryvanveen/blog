<?php

declare(strict_types=1);

namespace App\Application\Auth\Exceptions;

use InvalidArgumentException;

final class FailedLoginException extends InvalidArgumentException
{
    public static function credentialsDontMatch(): self
    {
        return new self('Credentials don\'t match our records');
    }
}
