<?php

namespace App\Blog\Models;

final class ArticleRepository
{
    public function allPublishedAndOrdered()
    {
        return Article::query()
            ->published()
            ->newestToOldest()
            ->with(['author'])
            ->get();
    }
}
