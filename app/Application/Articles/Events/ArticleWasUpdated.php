<?php

namespace App\Application\Articles\Events;

use App\Domain\Articles\Models\Article;

final class ArticleWasUpdated
{
    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }
}
