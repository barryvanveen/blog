<?php

declare(strict_types=1);

namespace App\Application\Http;

use Fig\Http\Message\StatusCodeInterface;

interface StatusCode extends StatusCodeInterface
{
    public const STATUS_PAGE_EXPIRED = 419;
}
