<?php

declare(strict_types=1);

namespace App\Application\Comments;

use App\Application\Comments\Events\CommentWasCreated;
use App\Application\Comments\Events\CommentWasUpdated;
use App\Application\Interfaces\EventBusInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Core\CollectionInterface;

final class CommentRepository implements CommentRepositoryInterface
{
    private QueryBuilderInterface $queryBuilder;

    private ModelMapperInterface $modelMapper;

    private EventBusInterface $eventBus;

    public function __construct(
        QueryBuilderInterface $builderFactory,
        ModelMapperInterface $modelMapper,
        EventBusInterface $eventBus
    ) {
        $this->queryBuilder = $builderFactory;
        $this->modelMapper = $modelMapper;
        $this->eventBus = $eventBus;
    }

    public function allOrdered(): CollectionInterface
    {
        $comments = $this->queryBuilder
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($comments);
    }

    public function getByUuid(string $uuid): Comment
    {
        $comment = $this->queryBuilder
            ->where('uuid', '=', $uuid)
            ->first();

        return $this->modelMapper->mapToDomainModel($comment);
    }

    public function insert(Comment $comment): void
    {
        $this->queryBuilder
            ->insert($comment->toArray());

        $this->eventBus->dispatch(new CommentWasCreated());
    }

    public function update(Comment $comment): void
    {
        $this->queryBuilder
            ->where('uuid', '=', $comment->uuid())
            ->update($comment->toArray());

        $this->eventBus->dispatch(new CommentWasUpdated($comment->uuid()));
    }
}
