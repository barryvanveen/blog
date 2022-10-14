<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;

final class ArticlesRssItemPresenter implements PresenterInterface
{
    public function __construct(
        private ArticleRepositoryInterface $repository,
        private UrlGeneratorInterface $urlGenerator,
        private DateTimeFormatterInterface $dateTimeFormatter,
    ) {
    }

    public function present(): array
    {
        $articles = $this->repository->allPublishedAndOrdered();

        return [
            'articles' => $this->parseArticles($articles),
        ];
    }

    private function parseArticles(CollectionInterface $articles): array
    {
        return $articles->map(fn(Article $article): array => [
            'title' => $article->title(),
            'publicationDate' => $this->publicationDateInAtomFormat($article),
            'description' => $article->description(),
            'url' => $this->articleShowUrl($article),
        ]);
    }

    private function publicationDateInAtomFormat(Article $article): string
    {
        return $this->dateTimeFormatter->metadata($article->publishedAt());
    }

    private function articleShowUrl(Article $article): string
    {
        return $this->urlGenerator->route('articles.show', [
            'uuid' => $article->uuid(),
            'slug' => $article->slug(),
        ]);
    }
}
