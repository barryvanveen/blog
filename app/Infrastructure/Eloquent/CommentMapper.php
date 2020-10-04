<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use App\Application\Comments\ModelMapperInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentStatus;
use App\Domain\Core\CollectionInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use DateTimeImmutable;

final class CommentMapper implements ModelMapperInterface
{
    public function mapToDomainModels(CollectionInterface $models): CollectionInterface
    {
        $domainModels = [];

        foreach ($models as $model) {
            $domainModels[] = $this->mapToDomainModel($model);
        }

        return new LaravelCollection($domainModels);
    }

    public function mapToDomainModel(array $model): Comment
    {
        return new Comment(
            $model['article_uuid'],
            $model['content'],
            new DateTimeImmutable($model['created_at']),
            $model['email'],
            $model['ip'],
            $model['name'],
            new CommentStatus((int) $model['status']),
            $model['uuid']
        );
    }
}
