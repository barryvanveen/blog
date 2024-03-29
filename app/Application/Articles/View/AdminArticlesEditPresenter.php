<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Articles\Requests\AdminArticleEditRequestInterface;

final class AdminArticlesEditPresenter implements PresenterInterface
{
    use PresentsArticleStatuses;

    public function __construct(
        private ArticleRepositoryInterface $repository,
        private UrlGeneratorInterface $urlGenerator,
        private AdminArticleEditRequestInterface $request,
        private SessionInterface $session,
    ) {
    }

    public function present(): array
    {
        /** @var Article $article */
        $article = $this->repository->getByUuid($this->request->uuid());

        return [
            'title' => 'Edit article',
            'url' => $this->urlGenerator->route('admin.articles.update', ['uuid' => $this->request->uuid()]),
            'statuses' => $this->statuses(),
            'article' => $this->article($article),
        ];
    }
    private function article(Article $article): array
    {
        return [
            'title' => $this->session->oldInput('title') ?? $article->title(),
            'published_at' => $this->session->oldInput('published_at') ?? $article->publishedAt()->format('Y-m-d H:i:s'),
            'description' => $this->session->oldInput('description') ?? $article->description(),
            'content' => $this->session->oldInput('content') ?? $article->content(),
            'status' => $this->session->oldInput('status') ?? (string) $article->status(),
        ];
    }
}
