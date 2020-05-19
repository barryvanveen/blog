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
    /** @var QueryBuilderInterface */
    private $queryBuilder;

    /** @var ModelMapperInterface */
    private $modelMapper;

    /** @var EventBusInterface */
    private $eventBus;

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
        $articles = $this->queryBuilder
            ->orderBy('published_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($articles);
    }

    public function allPublishedAndOrdered(): CollectionInterface
    {
        $articles = $this->queryBuilder
            ->where('status', '=', (string) ArticleStatus::published())
            ->where('published_at', '<=', (new DateTimeImmutable())->format(DateTimeImmutable::ATOM))
            ->orderBy('published_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($articles);
    }

    public function insert(Article $article): void
    {
        $this->queryBuilder
            ->insert($article->toArray());

        $this->eventBus->dispatch(new ArticleWasCreated());
    }

    public function update(Article $article): void
    {
        $this->queryBuilder
            ->where('uuid', '=', $article->uuid())
            ->update($article->toArray());

        $this->eventBus->dispatch(new ArticleWasUpdated($article->uuid()));
    }

    public function getPublishedByUuid(string $uuid): Article
    {
        $article = $this->queryBuilder
            ->where('uuid', '=', $uuid)
            ->where('status', '=', (string) ArticleStatus::published())
            ->where('published_at', '<=', (new DateTimeImmutable())->format(DateTimeImmutable::ATOM))
            ->first();

        return $this->modelMapper->mapToDomainModel($article);
    }

    public function getByUuid(string $uuid): Article
    {
        $article = $this->queryBuilder
            ->where('uuid', '=', $uuid)
            ->first();

        return $this->modelMapper->mapToDomainModel($article);
    }
}
