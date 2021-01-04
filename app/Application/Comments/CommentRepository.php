<?php

declare(strict_types=1);

namespace App\Application\Comments;

use App\Application\Comments\Events\CommentWasCreated;
use App\Application\Comments\Events\CommentWasUpdated;
use App\Application\Interfaces\EventBusInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Comments\CommentStatus;
use App\Domain\Core\CollectionInterface;

final class CommentRepository implements CommentRepositoryInterface
{
    private QueryBuilderInterface $queryBuilder;

    private ModelMapperInterface $modelMapper;

    private EventBusInterface $eventBus;

    public function __construct(
        QueryBuilderInterface $queryBuilder,
        ModelMapperInterface $modelMapper,
        EventBusInterface $eventBus
    ) {
        $this->queryBuilder = $queryBuilder;
        $this->modelMapper = $modelMapper;
        $this->eventBus = $eventBus;
    }

    public function allOrdered(): CollectionInterface
    {
        $comments = $this->queryBuilder
            ->new()
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($comments);
    }

    public function onlineOrderedByArticleUuid(string $uuid): CollectionInterface
    {
        $comments = $this->queryBuilder
            ->new()
            ->where('status', '=', (string) CommentStatus::published())
            ->where('article_uuid', '=', $uuid)
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->modelMapper->mapToDomainModels($comments);
    }

    public function getByUuid(string $uuid): Comment
    {
        $comment = $this->queryBuilder
            ->new()
            ->where('uuid', '=', $uuid)
            ->first();

        return $this->modelMapper->mapToDomainModel($comment);
    }

    public function insert(Comment $comment): void
    {
        $this->queryBuilder
            ->new()
            ->insert($comment->toArray());

        $this->eventBus->dispatch(new CommentWasCreated());
    }

    public function update(Comment $comment): void
    {
        $this->queryBuilder
            ->new()
            ->where('uuid', '=', $comment->uuid())
            ->update($comment->toArray());

        $this->eventBus->dispatch(new CommentWasUpdated($comment->uuid()));
    }
}
