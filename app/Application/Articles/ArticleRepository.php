<?php

declare(strict_types=1);

namespace App\Application\Articles;

use App\Application\Articles\Events\ArticleWasCreated;
use App\Application\Articles\Events\ArticleWasUpdated;
use App\Application\Interfaces\EventBusInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;
use DateTimeImmutable;

final class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private QueryBuilderInterface $queryBuilder,
        private ModelMapperInterface $modelMapper,
        private EventBusInterface $eventBus,
    ) {
    }

    public function allOrdered(): CollectionInterface
    {
        $articles = $this->queryBuilder
            ->new()
            ->orderBy('published_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($articles);
    }

    public function allPublishedAndOrdered(): CollectionInterface
    {
        $articles = $this->queryBuilder
            ->new()
            ->where('status', '=', (string) ArticleStatus::published())
            ->where('published_at', '<=', (new DateTimeImmutable())->format(DateTimeImmutable::ATOM))
            ->orderBy('published_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($articles);
    }

    public function insert(Article $article): void
    {
        $this->queryBuilder
            ->new()
            ->insert($article->toArray());

        $this->eventBus->dispatch(new ArticleWasCreated());
    }

    public function update(Article $article): void
    {
        $this->queryBuilder
            ->new()
            ->where('uuid', '=', $article->uuid())
            ->update($article->toArray());

        $this->eventBus->dispatch(new ArticleWasUpdated($article->uuid()));
    }

    public function getPublishedByUuid(string $uuid): Article
    {
        $article = $this->queryBuilder
            ->new()
            ->where('uuid', '=', $uuid)
            ->where('status', '=', (string) ArticleStatus::published())
            ->where('published_at', '<=', (new DateTimeImmutable())->format(DateTimeImmutable::ATOM))
            ->first();

        return $this->modelMapper->mapToDomainModel($article);
    }

    public function getByUuid(string $uuid): Article
    {
        $article = $this->queryBuilder
            ->new()
            ->where('uuid', '=', $uuid)
            ->first();

        return $this->modelMapper->mapToDomainModel($article);
    }
}
