<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use RuntimeException;

class HoneypotException extends RuntimeException
{
    public static function honeypotNotEmpty(): self
    {
        return new self('Honeypot was not empty');
    }
}
