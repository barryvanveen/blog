<?php

declare(strict_types=1);

namespace App\Application\Articles\Commands;

use App\Application\Core\CommandInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;

class UpdateArticle implements CommandInterface
{
    public function __construct(public string $content, public string $description, public DateTimeImmutable $publishedAt, public ArticleStatus $status, public string $title, public string $uuid)
    {
    }
}
