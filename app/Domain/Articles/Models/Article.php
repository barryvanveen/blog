<?php

declare(strict_types=1);

namespace App\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;

class Article
{
    /** @var string */
    private $content;

    /** @var string */
    private $description;

    /** @var DateTimeImmutable */
    private $publishedAt;

    /** @var string */
    private $slug;

    /** @var ArticleStatus */
    private $status;

    /** @var string */
    private $title;

    /** @var string */
    private $uuid;

    public function __construct(
        string $content,
        string $description,
        DateTimeImmutable $publishedAt,
        string $slug,
        ArticleStatus $status,
        string $title,
        string $uuid
    ) {
        $this->content = $content;
        $this->description = $description;
        $this->publishedAt = $publishedAt;
        $this->slug = $slug;
        $this->status = $status;
        $this->title = $title;
        $this->uuid = $uuid;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function publishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function isOnline(): bool
    {
        $now = new DateTimeImmutable();

        return $this->status->equals(ArticleStatus::published()) &&
            $now->getTimestamp() > $this->publishedAt->getTimestamp();
    }

    public function title(): string
    {
        return $this->title;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'description' => $this->description,
            'published_at' => $this->publishedAt,
            'slug' => $this->slug,
            'status' => $this->status,
            'title' => $this->title,
            'uuid' => $this->uuid,
        ];
    }
}
