<?php

declare(strict_types=1);

namespace App\Application\Pages\Listeners;

use RuntimeException;

class CacheInvalidationException extends RuntimeException
{
    public static function unkownSlug(string $slug): self
    {
        return new self("Unknown page slug: $slug, cannot clear cache");
    }
}
