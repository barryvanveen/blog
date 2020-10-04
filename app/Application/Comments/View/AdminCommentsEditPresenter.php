<?php

declare(strict_types=1);

namespace App\Application\Comments\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Comments\CommentStatus;
use App\Domain\Comments\Requests\AdminCommentEditRequestInterface;

final class AdminCommentsEditPresenter implements PresenterInterface
{
    private CommentRepositoryInterface $repository;
    private UrlGeneratorInterface $urlGenerator;
    private AdminCommentEditRequestInterface $request;
    private SessionInterface $session;
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(
        CommentRepositoryInterface $repository,
        UrlGeneratorInterface $urlGenerator,
        AdminCommentEditRequestInterface $request,
        SessionInterface $session,
        ArticleRepositoryInterface $articleRepository
    ) {
        $this->repository = $repository;
        $this->urlGenerator = $urlGenerator;
        $this->request = $request;
        $this->session = $session;
        $this->articleRepository = $articleRepository;
    }

    public function present(): array
    {
        $comment = $this->repository->getByUuid($this->request->uuid());

        return [
            'title' => 'Edit comment',
            'update_url' => $this->urlGenerator->route('admin.comments.update', ['uuid' => $this->request->uuid()]),
            'articles' => $this->articles(),
            'statuses' => $this->statuses(),
            'comment' => $this->comment($comment),
        ];
    }

    private function articles(): array
    {
        $articles = $this->articleRepository->allOrdered();

        $placeholder = [
            [
                'value' => '',
                'name' => '__Comment on article__',
            ],
        ];

        $articles = $articles->map(function (Article $article) {
            return [
                'value' => $article->uuid(),
                'name' => $article->title(),
            ];
        });

        return array_merge($placeholder, $articles);
    }

    private function statuses(): array
    {
        return [
            [
                'value' => (string) CommentStatus::unpublished(),
                'title' => 'Offline',
            ],
            [
                'value' => (string) CommentStatus::published(),
                'title' => 'Online',
            ],
        ];
    }

    private function comment(Comment $comment): array
    {
        return [
            'article_uuid' => $this->session->oldInput('article_uuid') ?? $comment->articleUuid(),
            'content' => $this->session->oldInput('content') ?? $comment->content(),
            'created_at' => $this->session->oldInput('created_at') ?? $comment->createdAt()->format('Y-m-d H:i:s'),
            'email' => $this->session->oldInput('email') ?? $comment->email(),
            'ip' => $this->session->oldInput('ip') ?? $comment->ip(),
            'name' => $this->session->oldInput('name') ?? $comment->name(),
            'status' => $this->session->oldInput('status') ?? (string) $comment->status(),
        ];
    }
}
