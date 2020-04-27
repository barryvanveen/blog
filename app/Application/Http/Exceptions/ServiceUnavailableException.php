<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use Exception;
use Throwable;

class ServiceUnavailableException extends Exception implements HttpExceptionInterface
{
    public static function create(Throwable $previous): HttpExceptionInterface
    {
        return new self('Service Unavailable', 503, $previous);
    }
}
