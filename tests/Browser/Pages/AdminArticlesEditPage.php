<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class AdminArticlesEditPage extends Page
{
    /** @var string */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function url()
    {
        return route('admin.articles.edit', ['uuid' => $this->uuid]);
    }

    public function elements()
    {
        return [
            '@header' => 'body h1',
            '@title' => 'main form[name="edit"] input[name="title"]',
            '@titleError' => 'main form[name="edit"] input[name="title"] + p',
            '@publicationDate' => 'main form[name="edit"] input[name="published_at"]',
            '@submit' => 'main form[name="edit"] input[type="submit"]',
        ];
    }
}
