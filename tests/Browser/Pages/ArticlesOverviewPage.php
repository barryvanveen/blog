<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class ArticlesOverviewPage extends Page
{
    public function url()
    {
        return route('articles.index');
    }

    public function elements()
    {
        return [
            '@title' => 'main h1',
            '@first-article-link' => 'main article h2 a',
        ];
    }
}
