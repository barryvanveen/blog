<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use App\Application\Articles\ModelMapperInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use DateTimeImmutable;

final class ArticleMapper implements ModelMapperInterface
{
    public function mapToDomainModels(CollectionInterface $models): CollectionInterface
    {
        $domainModels = [];

        foreach ($models as $model) {
            $domainModels[] = $this->mapToDomainModel($model);
        }

        return new LaravelCollection($domainModels);
    }

    public function mapToDomainModel(array $model): Article
    {
        return new Article(
            $model['content'],
            $model['description'],
            new DateTimeImmutable($model['published_at']),
            $model['slug'],
            new ArticleStatus((int) $model['status']),
            $model['title'],
            new DateTimeImmutable($model['updated_at']),
            $model['uuid']
        );
    }
}
