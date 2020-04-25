<?php

declare(strict_types=1);

namespace App\Application\Articles\Commands;

use App\Application\Core\CommandInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;

class UpdateArticle implements CommandInterface
{
    /** @var string */
    public $content;

    /** @var string */
    public $description;

    /** @var DateTimeImmutable */
    public $publishedAt;

    /** @var ArticleStatus */
    public $status;

    /** @var string */
    public $title;

    /** @var string */
    public $uuid;

    public function __construct(
        string $content,
        string $description,
        DateTimeImmutable $publishedAt,
        ArticleStatus $status,
        string $title,
        string $uuid
    ) {
        $this->content = $content;
        $this->description = $description;
        $this->publishedAt = $publishedAt;
        $this->status = $status;
        $this->title = $title;
        $this->uuid = $uuid;
    }
}
