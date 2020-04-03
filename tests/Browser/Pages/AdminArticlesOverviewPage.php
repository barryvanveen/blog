<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class AdminArticlesOverviewPage extends Page
{
    public function url()
    {
        return route('admin.articles.index');
    }

    public function elements()
    {
        return [
            '@title' => 'main h1',
            '@table' => 'main table',
            '@link' => 'main table a',
        ];
    }
}
