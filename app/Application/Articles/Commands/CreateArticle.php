<?php

declare(strict_types=1);

namespace App\Application\Articles\Commands;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Core\CommandInterface;
use DateTimeImmutable;

class CreateArticle implements CommandInterface
{
    /** @var string */
    public $authorUuid;

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

    public function __construct(
        string $authorUuid,
        string $content,
        string $description,
        DateTimeImmutable $publishedAt,
        ArticleStatus $status,
        string $title
    ) {
        $this->authorUuid = $authorUuid;
        $this->content = $content;
        $this->description = $description;
        $this->publishedAt = $publishedAt;
        $this->status = $status;
        $this->title = $title;
    }
}
