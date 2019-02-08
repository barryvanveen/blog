<?php

namespace App\Domain\Blog\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static ArticleStatus UNPUBLISHED()
 * @method static ArticleStatus PUBLISHED()
 */
class ArticleStatus extends Enum
{
    private const UNPUBLISHED = 0;
    private const PUBLISHED = 1;
}
