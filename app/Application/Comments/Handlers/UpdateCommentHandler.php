<?php

declare(strict_types=1);

namespace App\Application\Comments\Handlers;

use App\Application\Comments\Commands\UpdateComment;
use App\Application\Core\BaseCommandHandler;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;

final class UpdateCommentHandler extends BaseCommandHandler
{
    private CommentRepositoryInterface $repository;

    public function __construct(
        CommentRepositoryInterface $commentRepository
    ) {
        $this->repository = $commentRepository;
    }

    public function handleUpdateComment(UpdateComment $command): void
    {
        $comment = new Comment(
            $command->articleUuid,
            $command->content,
            $command->createdAt,
            $command->email,
            $command->ip,
            $command->name,
            $command->status,
            $command->uuid
        );

        $this->repository->update($comment);
    }
}
