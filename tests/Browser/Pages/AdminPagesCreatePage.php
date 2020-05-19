<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class AdminPagesCreatePage extends Page
{
    public function url()
    {
        return route('admin.pages.create');
    }

    public function elements()
    {
        return [
            '@header' => 'body h1',
            '@title' => 'main form[name="create"] input[name="title"]',
            '@titleError' => 'main form[name="create"] input[name="title"] + p',
            '@slug' => 'main form[name="create"] input[name="slug"]',
            '@description' => 'main form[name="create"] textarea[name="description"]',
            '@content' => 'main form[name="create"] textarea[name="content"]',
            '@submit' => 'main form[name="create"] input[type="submit"]',
        ];
    }
}
