<?php

declare(strict_types=1);

namespace App\Application\Articles\Commands;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Core\CommandInterface;
use Carbon\Carbon;

class CreateArticle implements CommandInterface
{
    /** @var int */
    public $authorId;

    /** @var string */
    public $content;

    /** @var string */
    public $description;

    /** @var Carbon */
    public $publishedAt;

    /** @var ArticleStatus */
    public $status;

    /** @var string */
    public $title;

    public function __construct(
        int $authorId,
        string $content,
        string $description,
        Carbon $publishedAt,
        ArticleStatus $status,
        string $title
    ) {
        $this->authorId = $authorId;
        $this->content = $content;
        $this->description = $description;
        $this->publishedAt = $publishedAt;
        $this->status = $status;
        $this->title = $title;
    }
}
