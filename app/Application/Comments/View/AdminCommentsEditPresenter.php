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
    use PresentsArticles, PresentsCommentStatuses;

    public function __construct(private CommentRepositoryInterface $repository, private UrlGeneratorInterface $urlGenerator, private AdminCommentEditRequestInterface $request, private SessionInterface $session, private ArticleRepositoryInterface $articleRepository)
    {
    }

    public function present(): array
    {
        $comment = $this->repository->getByUuid($this->request->uuid());

        return [
            'title' => 'Edit comment',
            'url' => $this->urlGenerator->route('admin.comments.update', ['uuid' => $this->request->uuid()]),
            'articles' => $this->articles(),
            'statuses' => $this->statuses(),
            'comment' => $this->comment($comment),
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
