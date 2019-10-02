<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;

final class ArticlesIndexPresenter implements PresenterInterface
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function present(): array
    {
        return [
            'articles' => $this->repository->allPublishedAndOrdered(),
        ];
    }
}
