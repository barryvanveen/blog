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
            '@title' => '#title',
            '@first-article-link' => 'ul li article a.read-article:first-of-type',
        ];
    }
}
