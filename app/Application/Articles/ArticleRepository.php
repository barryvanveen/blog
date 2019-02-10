<?php

namespace App\Application\Articles;

use App\Application\Articles\Events\ArticleWasCreated;
use App\Application\Articles\Events\ArticleWasUpdated;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;

final class ArticleRepository implements ArticleRepositoryInterface
{
    public function allPublishedAndOrdered()
    {
        return Article::query()
            ->published()
            ->newestToOldest()
            ->with(['author'])
            ->get();
    }

    public function save(Article $article)
    {
        $articleExistedBeforeSave = $article->exists;

        $article->save();

        if ($articleExistedBeforeSave) {
            event(new ArticleWasUpdated($article));
        } else {
            event(new ArticleWasCreated($article));
        }
    }
}
