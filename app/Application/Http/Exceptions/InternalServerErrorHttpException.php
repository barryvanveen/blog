<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use Exception;
use Throwable;

class InternalServerErrorHttpException extends Exception implements HttpExceptionInterface
{
    public static function create(Throwable $previous): self
    {
        return new self('Internal Server Error', 500, $previous);
    }
}
