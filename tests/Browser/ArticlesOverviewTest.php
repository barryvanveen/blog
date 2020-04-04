<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ArticlesOverviewPage;
use Tests\DuskTestCase;

class ArticlesOverviewTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function viewArticles(): void
    {
        /** @var ArticleEloquentModel[] $visibleArticles */
        $visibleArticles = factory(ArticleEloquentModel::class, 2)->states('published_in_past')->create();
        /** @var ArticleEloquentModel[] $unvisibleArticles */
        $unvisibleArticles = factory(ArticleEloquentModel::class, 2)->states('published_in_future')->create();

        $this->browse(function (Browser $browser) use ($visibleArticles, $unvisibleArticles) {
            $browser
                ->visit(new ArticlesOverviewPage())
                ->assertSeeIn('@title', 'Articles');

            foreach ($visibleArticles as $article) {
                $browser->assertSee($article->title);
            }

            foreach ($unvisibleArticles as $article) {
                $browser->assertDontSee($article->title);
            }
        });
    }

    /** @test */
    public function clickToArticlePage(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = factory(ArticleEloquentModel::class)->create();

        $this->browse(function (Browser $browser) use ($article) {
            $browser
                ->visit(new ArticlesOverviewPage())
                ->assertSee($article->title)
                ->click('@first-article-link')
                ->assertRouteIs('articles.show', ['uuid' => $article->uuid, 'slug' => $article->slug])
                ->assertSee($article->title);
        });
    }

    /** @test */
    public function redirectToCorrectSlug():void
    {
        /** @var ArticleEloquentModel $article */
        $article = factory(ArticleEloquentModel::class)->create();

        $this->browse(function (Browser $browser) use ($article) {
            $browser
                ->visitRoute('articles.show', ['uuid' => $article->uuid, 'slug' => 'incorrect-slug'])
                ->assertRouteIs('articles.show', ['uuid' => $article->uuid, 'slug' => $article->slug])
                ->assertSee($article->title);
        });
    }
}
