<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use App\Application\Http\StatusCode;
use Exception;
use Throwable;

class InternalServerErrorHttpException extends Exception implements HttpExceptionInterface
{
    public static function create(Throwable $previous): HttpExceptionInterface
    {
        return new self('Internal Server Error', StatusCode::STATUS_INTERNAL_SERVER_ERROR, $previous);
    }
}
