<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class AdminPagesOverviewPage extends Page
{
    public function url()
    {
        return route('admin.pages.index');
    }

    public function elements()
    {
        return [
            '@title' => 'main h1',
            '@table' => 'main table',
            '@createLink' => 'main h1 + a',
            '@editLink' => 'main table a',
        ];
    }
}
