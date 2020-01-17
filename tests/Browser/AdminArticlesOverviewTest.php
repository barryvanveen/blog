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
}
