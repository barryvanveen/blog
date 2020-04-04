<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminArticlesOverviewPage;
use Tests\DuskTestCase;

class AdminArticlesOverviewTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @var UserEloquentModel */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(UserEloquentModel::class)->create();
    }

    /** @test */
    public function pageIsNotVisibleWithoutAuthentication(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new AdminArticlesOverviewPage())
                ->assertRouteIs('login');
        });
    }

    /** @test */
    public function viewArticles(): void
    {
        /** @var ArticleEloquentModel[] $visibleArticles */
        $articles = factory(ArticleEloquentModel::class, 3)->create();

        $this->browse(function (Browser $browser) use ($articles) {
            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminArticlesOverviewPage())
                ->assertSeeIn('@title', 'Articles');

            foreach ($articles as $article) {
                $browser->assertSeeIn('@table', $article->title);
            }
        });
    }

    /** @test */
    public function editArticle(): void
    {
        $article = factory(ArticleEloquentModel::class)->create();

        $this->browse(function (Browser $browser) use ($article) {
            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminArticlesOverviewPage())
                ->click('@editLink')
                ->assertRouteIs('admin.articles.edit', $article->uuid);
        });
    }

    /** @test */
    public function createArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminArticlesOverviewPage())
                ->click('@createLink')
                ->assertRouteIs('admin.articles.create');
        });
    }
}
