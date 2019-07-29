<?php

declare(strict_types=1);

namespace App\Domain\Articles;

use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;

interface ArticleRepositoryInterface
{
    public function allPublishedAndOrdered(): CollectionInterface;

    public function insert(Article $article): void;

    public function update(Article $article): void;

    public function getByUuid(string $uuid): Article;
}
