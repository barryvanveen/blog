<?php

declare(strict_types=1);

namespace App\Application\Articles\ViewModels;

use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Core\CollectionInterface;

final class ArticlesIndexViewModel
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function articles(): CollectionInterface
    {
        return $this->repository->allPublishedAndOrdered();
    }

    public function toArray(): array
    {
        return [
            'articles' => $this->articles(),
        ];
    }
}
