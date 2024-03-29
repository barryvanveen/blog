<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class AdminPagesEditPage extends Page
{
    public function __construct(
        private string $slug,
    ) {
    }

    public function url()
    {
        return route('admin.pages.edit', ['slug' => $this->slug]);
    }

    public function elements()
    {
        return [
            '@header' => 'body h1',
            '@title' => 'main form[name="edit"] input[name="title"]',
            '@titleError' => 'main form[name="edit"] input[name="title"] + p',
            '@content' => 'main form[name="edit"] textarea[name="content"]',
            '@submit' => 'main form[name="edit"] input[type="submit"]',
        ];
    }
}
