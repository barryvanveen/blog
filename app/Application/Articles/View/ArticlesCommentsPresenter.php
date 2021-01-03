<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Core\CollectionInterface;

final class ArticlesCommentsPresenter implements PresenterInterface
{
    private ArticleShowRequestInterface $request;

    private DateTimeFormatterInterface $dateTimeFormatter;

    private CommentRepositoryInterface $commentRepository;

    public function __construct(
        ArticleShowRequestInterface $request,
        DateTimeFormatterInterface $dateTimeFormatter,
        CommentRepositoryInterface $commentRepository
    ) {
        $this->request = $request;
        $this->dateTimeFormatter = $dateTimeFormatter;
        $this->commentRepository = $commentRepository;
    }

    public function present(): array
    {
        $comments = $this->commentRepository->onlineOrderedByArticleUuid($this->request->uuid());

        return [
            'total' => $comments->count(),
            'comments' => $this->comments($comments),
        ];
    }

    private function comments(CollectionInterface $comments): array
    {
        $presentableComments = [];

        foreach ($comments as $comment) {
            $presentableComments[] = [
                'name' => $comment->name(),
                'date_human_readable' => $this->dateTimeFormatter->humanReadable($comment->createdAt()),
                'date_meta' => $this->dateTimeFormatter->metadata($comment->createdAt()),
                'content' => $comment->content(),
                'uuid' => $comment->uuid(),
            ];
        }

        return $presentableComments;
    }
}
