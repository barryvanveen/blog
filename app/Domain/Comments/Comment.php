<?php

declare(strict_types=1);

namespace App\Domain\Comments;

use DateTimeImmutable;

class Comment
{
    /** @var string */
    private $articleUuid;

    /** @var string */
    private $content;

    /** @var DateTimeImmutable */
    private $createdAt;

    /** @var string */
    private $email;

    /** @var string */
    private $ip;

    /** @var string */
    private $name;

    /** @var CommentStatus */
    private $status;

    /** @var string */
    private $uuid;

    public function __construct(
        string $article_uuid,
        string $content,
        DateTimeImmutable $createdAt,
        string $email,
        string $ip,
        string $name,
        CommentStatus $status,
        string $uuid
    ) {
        $this->articleUuid = $article_uuid;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->email = $email;
        $this->ip = $ip;
        $this->name = $name;
        $this->status = $status;
        $this->uuid = $uuid;
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
