<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class ArticlesStorePage extends Page
{
    public function url()
    {
        return route('articles.store');
    }
}
