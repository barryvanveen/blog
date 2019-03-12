<?php

declare(strict_types=1);

namespace App\Application\Articles\Events;

use App\Domain\Articles\Models\Article;
use App\Domain\Core\EventInterface;

final class ArticleWasCreated implements EventInterface
{
    /** @var Article */
    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }
}
