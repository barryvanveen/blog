<?php

declare(strict_types=1);

namespace App\Application\Articles;

use App\Application\Articles\Events\ArticleWasCreated;
use App\Application\Articles\Events\ArticleWasUpdated;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class ArticleRepository implements ArticleRepositoryInterface
{
    public function allPublishedAndOrdered(): Collection
    {
        return Article::query()
            ->where('status', '=', ArticleStatus::PUBLISHED())
            ->where('published_at', '<=', Carbon::now()->toDateTimeString())
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function save(Article $article): void
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
