<?php

declare(strict_types=1);

namespace App\Application\Articles\ViewModels;

use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;

final class ArticlesItemViewModel
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

    public function article(): Article
    {
        return $this->repository->getByUuid($this->request->uuid());
    }
}
