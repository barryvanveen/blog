<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;

final class AdminArticlesIndexPresenter implements PresenterInterface
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
        return [
            'title' => 'Articles',
            'articles' => $this->articles(),
        ];
    }

    private function articles(): array
    {
        /** @var Article[] $articles */
        $articles = $this->repository->allOrdered();

        $presentableArticles = [];

        foreach ($articles as $article) {
            $presentableArticles[] = [
                'uuid' => $article->uuid(),
                'title' => $article->title(),
                'status' => $article->isOnline() ? 'online' : 'offline',
                'published_at' => $article->publishedAt()->format('M d, Y'),
                'edit_url' => $this->urlGenerator->route('admin.articles.edit', ['uuid' => $article->uuid()]),
            ];
        }

        return $presentableArticles;
    }
}
