<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;
use DateTimeInterface;

final class ArticlesRssItemPresenter implements PresenterInterface
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        ArticleRepositoryInterface $repository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->repository = $repository;
        $this->urlGenerator = $urlGenerator;
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
        return $articles->map(function (Article $article): array {
            return [
                'title' => $article->title(),
                'publicationDate' => $this->publicationDateInAtomFormat($article),
                'description' => $article->description(),
                'url' => $this->articleShowUrl($article),
            ];
        });
    }

    private function publicationDateInAtomFormat(Article $article): string
    {
        return $article->publishedAt()->format(DateTimeInterface::ATOM);
    }

    private function articleShowUrl(Article $article): string
    {
        return $this->urlGenerator->route('articles.show', [
            'uuid' => $article->uuid(),
            'slug' => $article->slug(),
        ]);
    }
}
