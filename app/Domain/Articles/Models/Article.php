<?php

declare(strict_types=1);

namespace App\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;
use DateTimeInterface;

class Article
{
    public function __construct(private string $content, private string $description, private DateTimeInterface $publishedAt, private string $slug, private ArticleStatus $status, private string $title, private DateTimeInterface $updatedAt, private string $uuid)
    {
    }

    public function content(): string
    {
        return $this->content;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function publishedAt(): DateTimeInterface
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

    public function status(): ArticleStatus
    {
        return $this->status;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function updatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
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
            'updated_at' => $this->updatedAt,
            'uuid' => $this->uuid,
        ];
    }
}
