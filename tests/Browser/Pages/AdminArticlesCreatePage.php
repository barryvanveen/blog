<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class AdminArticlesCreatePage extends Page
{
    public function url()
    {
        return route('admin.articles.create');
    }

    public function elements()
    {
        return [
            '@header' => 'body h1',
            '@title' => 'main form[name="create"] input[name="title"]',
            '@titleError' => 'main form[name="create"] input[name="title"] + p',
            '@publicationDate' => 'main form[name="create"] input[name="published_at"]',
            '@status' => 'main form[name="create"] input[name="status"]',
            '@description' => 'main form[name="create"] textarea[name="description"]',
            '@content' => 'main form[name="create"] textarea[name="content"]',
            '@submit' => 'main form[name="create"] input[type="submit"]',
        ];
    }
}
