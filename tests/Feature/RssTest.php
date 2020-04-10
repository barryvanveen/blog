<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Rss extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeArticleRssFeed(): void
    {
        /** @var ArticleEloquentModel[] $visibleArticle */
        factory(ArticleEloquentModel::class)->states('published_in_past')->create([
            'title' => 'FooArticle',
        ]);

        /** @var ArticleEloquentModel[] $unvisibleArticles */
        factory(ArticleEloquentModel::class)->states('published_in_future')->create([
            'title' => 'BarArticle',
        ]);

        $response = $this->get(route('articles.rss'));

        $response->assertOk();
        $response->assertSee('FooArticle');
        $response->assertDontSee('BarArticle');
    }
}
