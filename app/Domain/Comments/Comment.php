<?php

declare(strict_types=1);

namespace App\Domain\Comments;

use DateTimeImmutable;

class Comment
{
    public function __construct(
        private string $articleUuid,
        private string $content,
        private DateTimeImmutable $createdAt,
        private string $email,
        private string $ip,
        private string $name,
        private CommentStatus $status,
        private string $uuid,
    ) {
    }

    public function articleUuid(): string
    {
        return $this->articleUuid;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function ip(): string
    {
        return $this->ip;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isOnline(): bool
    {
        return $this->status->equals(CommentStatus::published());
    }

    public function status(): CommentStatus
    {
        return $this->status;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function toArray(): array
    {
        return [
            'article_uuid' => $this->articleUuid,
            'content' => $this->content,
            'created_at' => $this->createdAt,
            'email' => $this->email,
            'ip' => $this->ip,
            'name' => $this->name,
            'status' => $this->status,
            'uuid' => $this->uuid,
        ];
    }
}
