<?php

declare(strict_types=1);

namespace App\Application\Articles;

use App\Application\Articles\Events\ArticleWasCreated;
use App\Application\Articles\Events\ArticleWasUpdated;
use App\Application\Interfaces\ModelMapperInterface;
use App\Application\Interfaces\QueryBuilderFactoryInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;
use DateTimeImmutable;

final class ArticleRepository implements ArticleRepositoryInterface
{
    /** @var QueryBuilderFactoryInterface */
    private $builderFactory;

    /** @var ModelMapperInterface */
    private $modelMapper;

    public function __construct(QueryBuilderFactoryInterface $builderFactory, ModelMapperInterface $modelMapper)
    {
        $this->builderFactory = $builderFactory;
        $this->modelMapper = $modelMapper;
    }

    public function allOrdered(): CollectionInterface
    {
        $articles = $this->builderFactory
            ->table('articles')
            ->orderBy('published_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($articles);
    }

    public function allPublishedAndOrdered(): CollectionInterface
    {
        $articles = $this->builderFactory
            ->table('articles')
            ->where('status', '=', (string) ArticleStatus::published())
            ->where('published_at', '<=', (new DateTimeImmutable())->format(DateTimeImmutable::ATOM))
            ->orderBy('published_at', 'desc')
            ->get();

        return $this->modelMapper->mapToDomainModels($articles);
    }

    public function insert(Article $article): void
    {
        $this->builderFactory
            ->table('articles')
            ->insert($article->toArray());

        event(new ArticleWasCreated($article));
    }

    public function update(Article $article): void
    {
        $this->builderFactory
            ->table('articles')
            ->where('uuid', '=', $article->uuid())
            ->update($article->toArray());

        event(new ArticleWasUpdated($article));
    }

    public function getByUuid(string $uuid): Article
    {
        $article = $this->builderFactory
            ->table('articles')
            ->where('uuid', '=', $uuid)
            ->first();

        return $this->modelMapper->mapToDomainModel($article);
    }
}
