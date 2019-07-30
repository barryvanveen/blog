<?php

declare(strict_types=1);

namespace App\Application\Core;

use RuntimeException;

class RecordNotFoundException extends RuntimeException
{
    public static function emptyResultSet(): self
    {
        return new self('Query returned no results');
    }
}
