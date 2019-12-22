<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use DateTime;

final class ArticlesItemPresenter implements PresenterInterface
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var ArticleShowRequestInterface */
    private $request;

    public function __construct(ArticleRepositoryInterface $repository, ArticleShowRequestInterface $request)
    {
        $this->repository = $repository;
        $this->request = $request;
    }

    public function present(): array
    {
        $article = $this->repository->getByUuid($this->request->uuid());

        return [
            'title' => $article->title(),
            'publicationDateInAtomFormat' => $article->publishedAt()->format(DateTime::ATOM),
            'publicationDateInHumanFormat' => $article->publishedAt()->format('M d, Y'),
            'content' => $article->content(),
        ];
    }
}
