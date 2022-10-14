<?php

declare(strict_types=1);

namespace App\Application\Comments\View;

use App\Domain\Articles\Models\Article;

trait PresentsArticles
{
    private function articles(): array
    {
        $articles = $this->articleRepository->allOrdered();

        $placeholder = [
            [
                'value' => '',
                'name' => '__Comment on article__',
            ],
        ];

        $articles = $articles->map(fn(Article $article) => [
            'value' => $article->uuid(),
            'name' => $article->title(),
        ]);

        return array_merge($placeholder, $articles);
    }
}
