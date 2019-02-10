<?php

namespace App\Domain\Articles;

use App\Domain\Articles\Models\Article;

interface ArticleRepositoryInterface
{
    public function allPublishedAndOrdered();

    public function save(Article $article);
}
