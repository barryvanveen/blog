<?php

declare(strict_types=1);

namespace App\Application\Comments;

use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Core\CollectionInterface;

final class CommentRepository implements CommentRepositoryInterface
{
    /** @var QueryBuilderInterface */
    private $queryBuilder;

    /** @var ModelMapperInterface */
    private $modelMapper;

    public function __construct(
        QueryBuilderInterface $builderFactory,
        ModelMapperInterface $modelMapper
    ) {
        $this->queryBuilder = $builderFactory;
        $this->modelMapper = $modelMapper;
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
}
