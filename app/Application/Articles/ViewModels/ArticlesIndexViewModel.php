<?php

declare(strict_types=1);

namespace App\Application\Articles\ViewModels;

use App\Domain\Articles\ArticleRepositoryInterface;
use Illuminate\Contracts\Support\Arrayable;

final class ArticlesIndexViewModel implements Arrayable
{
    private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function articles()
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
