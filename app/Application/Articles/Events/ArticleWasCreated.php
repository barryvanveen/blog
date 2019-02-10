<?php

namespace App\Application\Articles\Events;

use App\Domain\Articles\Models\Article;

final class ArticleWasCreated
{
    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }
}
