<?php

declare(strict_types=1);

namespace App\Application\Articles\ViewModels;

use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;

final class ArticlesItemViewModel
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function article(string $uuid): Article
    {
        return $this->repository->getByUuid($uuid);
    }

    public function toArray(string $uuid): array
    {
        return [
            'article' => $this->article($uuid),
        ];
    }
}
