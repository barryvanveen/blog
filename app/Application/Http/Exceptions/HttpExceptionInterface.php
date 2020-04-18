<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use Throwable;

interface HttpExceptionInterface
{
    public static function create(Throwable $previous);
}
