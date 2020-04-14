<?php

declare(strict_types=1);

namespace App\Domain\Articles;

use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;

interface ArticleRepositoryInterface
{
    public function allOrdered(): CollectionInterface;

    public function allPublishedAndOrdered(): CollectionInterface;

    public function insert(Article $article): void;

    public function update(Article $article): void;

    public function getPublishedByUuid(string $uuid): Article;

    public function getByUuid(string $uuid): Article;
}
