<?php

declare(strict_types=1);

namespace App\Application\Comments\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;

final class AdminCommentsIndexPresenter implements PresenterInterface
{
    private CommentRepositoryInterface $commentRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        CommentRepositoryInterface $repository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->commentRepository = $repository;
        $this->urlGenerator = $urlGenerator;
    }

    public function present(): array
    {
        return [
            'title' => 'Comments',
            'comments' => $this->comments(),
            'create_url' => $this->urlGenerator->route('admin.comments.create'),
        ];
    }

    private function comments(): array
    {
        /** @var Comment[] $comments */
        $comments = $this->commentRepository->allOrdered();

        $presentableComments = [];

        foreach ($comments as $comment) {
            $presentableComments[] = [
                'is_online' => $comment->isOnline(),
                'uuid' => $comment->uuid(),
                'name' => $comment->name(),
                'content' => substr($comment->content(), 0, 100),
                'created_at' => $comment->createdAt()->format('M d, Y'),
                'edit_url' => $this->urlGenerator->route('admin.comments.edit', ['uuid' => $comment->uuid()]),
            ];
        }

        return $presentableComments;
    }
}
