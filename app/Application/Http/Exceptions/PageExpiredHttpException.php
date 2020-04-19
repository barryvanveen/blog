<?php

declare(strict_types=1);

namespace App\Application\Http\Exceptions;

use Exception;
use Throwable;

class PageExpiredHttpException extends Exception implements HttpExceptionInterface
{
    public static function create(Throwable $previous): HttpExceptionInterface
    {
        return new self('Page Expired', 419, $previous);
    }
}
