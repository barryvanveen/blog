<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;

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
        return [
            'article' => $this->repository->getByUuid($this->request->uuid()),
        ];
    }
}
