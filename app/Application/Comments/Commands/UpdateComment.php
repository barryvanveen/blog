<?php

declare(strict_types=1);

namespace App\Application\Comments\Commands;

use App\Application\Core\CommandInterface;
use App\Domain\Comments\CommentStatus;
use DateTimeImmutable;

class UpdateComment implements CommandInterface
{
    public string $articleUuid;
    public string $content;
    public DateTimeImmutable $createdAt;
    public string $email;
    public string $ip;
    public string $name;
    public CommentStatus $status;
    public string $uuid;

    public function __construct(
        string $articleUuid,
        string $content,
        DateTimeImmutable $createdAt,
        string $email,
        string $ip,
        string $name,
        CommentStatus $status,
        string $uuid
    ) {
        $this->articleUuid = $articleUuid;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->email = $email;
        $this->ip = $ip;
        $this->name = $name;
        $this->status = $status;
        $this->uuid = $uuid;
    }
}
