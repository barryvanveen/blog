<?php

declare(strict_types=1);

namespace App\Application\Articles\ViewModels;

use App\Domain\Articles\ArticleRepositoryInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

final class ArticlesIndexViewModel implements Arrayable
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function articles(): Collection
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
