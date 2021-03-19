<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class ArticleItemPage extends Page
{
    private string $uuid;
    private string $slug;

    public function __construct(string $uuid, string $slug)
    {
        $this->uuid = $uuid;
        $this->slug = $slug;
    }

    public function url()
    {
        return route('articles.show', ['uuid' => $this->uuid, 'slug' => $this->slug]);
    }

    public function elements()
    {
        return [
            '@articleHeading' => 'main article h1',
            '@name' => 'form[name="comment"] input[name="name"]',
            '@nameError' => 'form[name="comment"] input[name="name"] + p',
            '@email' => 'form[name="comment"] input[name="email"]',
            '@emailError' => 'form[name="comment"] input[name="email"] + p',
            '@content' => 'form[name="comment"] textarea[name="content"]',
            '@contentError' => 'form[name="comment"] textarea[name="content"] + p',
            '@submit' => 'form[name="comment"] input[type="submit"]',
        ];
    }
}
