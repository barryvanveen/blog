<?php

declare(strict_types=1);

namespace App\Domain\Comments;

use App\Domain\Core\CollectionInterface;

interface CommentRepositoryInterface
{
    public function allOrdered(): CollectionInterface;

    public function getByUuid(string $uuid): Comment;
}
