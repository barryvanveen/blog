<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use RuntimeException;

final class ViewException extends RuntimeException
{
    public static function renderShouldReturnString(string $view): self
    {
        return new self('View '.$view.' could not be rendered into a string.');
    }
}
