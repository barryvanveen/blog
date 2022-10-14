<?php

declare(strict_types=1);

namespace App\Application\Comments\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Comments\CommentStatus;

final class AdminCommentsCreatePresenter implements PresenterInterface
{
    use PresentsArticles, PresentsCommentStatuses;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private SessionInterface $session,
        private ArticleRepositoryInterface $articleRepository,
    ) {
    }

    public function present(): array
    {
        return [
            'title' => 'New comment',
            'url' => $this->urlGenerator->route('admin.comments.store'),
            'articles' => $this->articles(),
            'statuses' => $this->statuses(),
            'comment' => $this->comment(),
        ];
    }

    private function comment(): array
    {
        return [
            'article_uuid' => $this->session->oldInput('article_uuid', ''),
            'content' => $this->session->oldInput('content', ''),
            'created_at' => $this->session->oldInput('created_at', ''),
            'email' => $this->session->oldInput('email', ''),
            'ip' => $this->session->oldInput('ip', ''),
            'name' => $this->session->oldInput('name', ''),
            'status' => $this->session->oldInput('status', (string) CommentStatus::unpublished()),
        ];
    }
}
