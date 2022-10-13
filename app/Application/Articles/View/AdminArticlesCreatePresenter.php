<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;

final class AdminArticlesCreatePresenter implements PresenterInterface
{
    use PresentsArticleStatuses;

    public function __construct(private UrlGeneratorInterface $urlGenerator, private SessionInterface $session)
    {
    }

    public function present(): array
    {
        return [
            'title' => 'New article',
            'url' => $this->urlGenerator->route('admin.articles.store'),
            'statuses' => $this->statuses(),
            'article' => $this->article(),
        ];
    }

    private function article(): array
    {
        return [
            'title' => $this->session->oldInput('title', ''),
            'published_at' => $this->session->oldInput('published_at', (new DateTimeImmutable())->format('Y-m-d H:i:s')),
            'description' => $this->session->oldInput('description', ''),
            'content' => $this->session->oldInput('content', ''),
            'status' => $this->session->oldInput('status', (string) ArticleStatus::unpublished()),
        ];
    }
}
