<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use Exception;
use Throwable;

class TooManyRequestsHttpException extends Exception implements HttpExceptionInterface
{
    public static function create(Throwable $previous): HttpExceptionInterface
    {
        return new self('Too Many Requests', 429, $previous);
    }
}
