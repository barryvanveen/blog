<?php

declare(strict_types=1);

namespace App\Application\Comments\Handlers;

use App\Application\Comments\Commands\CreateComment;
use App\Application\Core\BaseCommandHandler;
use App\Application\Core\UniqueIdGeneratorInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;

final class CreateCommentHandler extends BaseCommandHandler
{
    private UniqueIdGeneratorInterface $uniqueIdGenerator;

    private CommentRepositoryInterface $repository;

    public function __construct(
        UniqueIdGeneratorInterface $uniqueIdGenerator,
        CommentRepositoryInterface $repository
    ) {
        $this->uniqueIdGenerator = $uniqueIdGenerator;
        $this->repository = $repository;
    }

    public function handleCreateComment(CreateComment $command): void
    {
        $comment = new Comment(
            $command->articleUuid,
            $command->content,
            $command->createdAt,
            $command->email,
            $command->ip,
            $command->name,
            $command->status,
            $this->uniqueIdGenerator->generate()
        );

        $this->repository->insert($comment);
    }
}
