<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RssTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeArticleRssFeed(): void
    {
        factory(ArticleEloquentModel::class)->states(['published', 'published_in_past'])->create([
            'title' => 'FooArticle',
        ]);

        factory(ArticleEloquentModel::class)->states(['unpublished', 'published_in_future'])->create([
            'title' => 'BarArticle',
        ]);

        $response = $this->get(route('articles.rss'));

        $response->assertOk();
        $response->assertSee('FooArticle');
        $response->assertDontSee('BarArticle');
    }
}
