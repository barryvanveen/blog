<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use App\Application\Http\StatusCode;
use Exception;
use Throwable;

class ForbiddenHttpException extends Exception implements HttpExceptionInterface
{
    public static function create(Throwable $previous): HttpExceptionInterface
    {
        return new self('Forbidden', StatusCode::STATUS_FORBIDDEN, $previous);
    }
}
