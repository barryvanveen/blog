<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Factories\ArticleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RssTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeArticleRssFeed(): void
    {
        ArticleFactory::new()->published()->publishedInPast()->create([
            'title' => 'FooArticle',
        ]);

        ArticleFactory::new()->unpublished()->publishedInFuture()->create([
            'title' => 'BarArticle',
        ]);

        $response = $this->get(route('articles.rss'));

        $response->assertOk();
        $response->assertSee('FooArticle');
        $response->assertDontSee('BarArticle');
    }
}
