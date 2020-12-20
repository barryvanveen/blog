<?php

declare(strict_types=1);

namespace App\Domain\Comments;

use App\Domain\Core\CollectionInterface;

interface CommentRepositoryInterface
{
    public function allOrdered(): CollectionInterface;

    public function onlineOrderedByArticleUuid(string $uuid): CollectionInterface;

    public function getByUuid(string $uuid): Comment;

    public function insert(Comment $comment): void;

    public function update(Comment $comment): void;
}
