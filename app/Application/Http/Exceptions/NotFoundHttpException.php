<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use Exception;
use Throwable;

class NotFoundHttpException extends Exception implements HttpExceptionInterface
{
    public static function create(Throwable $previous): self
    {
        return new self('Not Found', 404, $previous);
    }
}
