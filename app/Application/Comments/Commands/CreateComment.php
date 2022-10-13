<?php

declare(strict_types=1);

namespace App\Application\Comments\Commands;

use App\Application\Core\CommandInterface;
use App\Domain\Comments\CommentStatus;
use DateTimeImmutable;

class CreateComment implements CommandInterface
{
    public function __construct(public string $articleUuid, public string $content, public DateTimeImmutable $createdAt, public string $email, public string $ip, public string $name, public CommentStatus $status)
    {
    }
}
