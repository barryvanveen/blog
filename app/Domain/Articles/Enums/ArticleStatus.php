<?php

declare(strict_types=1);

namespace App\Domain\Articles\Enums;

use App\Domain\Core\Enum;

class ArticleStatus extends Enum
{
    private const UNPUBLISHED = 0;
    private const PUBLISHED = 1;

    public static function unpublished(): self
    {
        return new self(self::UNPUBLISHED);
    }

    public static function published(): self
    {
        return new self(self::PUBLISHED);
    }
}
