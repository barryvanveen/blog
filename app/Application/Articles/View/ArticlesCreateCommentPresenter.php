<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;

final class ArticlesCreateCommentPresenter implements PresenterInterface
{
    private ArticleShowRequestInterface $request;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        ArticleShowRequestInterface $request,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
    }

    public function present(): array
    {
        return [
            'create_comment_url' => $this->urlGenerator->route('comments.store'),
            'article_uuid' => $this->request->uuid(),
        ];
    }
}
