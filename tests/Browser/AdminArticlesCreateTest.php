<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminArticlesCreatePage;
use Tests\DuskTestCase;

class AdminArticlesCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @var UserEloquentModel */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    /** @test */
    public function pageIsNotVisibleWithoutAuthentication(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new AdminArticlesCreatePage())
                ->assertRouteIs('login');
        });
    }

    /** @test */
    public function createArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $title = 'My new article title';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminArticlesCreatePage())
                ->assertRouteIs('admin.articles.create')
                ->assertSee('New article')
                ->type('@title', $title)
                ->type('@publicationDate', '2020-04-04 11:51:00')
                ->assertRadioSelected('@status', (string) ArticleStatus::published())
                ->type('@description', 'Description')
                ->type('@content', 'Content')
                ->click('@submit')
                ->assertRouteIs('admin.articles.index')
                ->assertSee($title);
        });
    }

    /** @test */
    public function createArticleValidation(): void
    {
        $this->browse(function (Browser $browser) {
            $publicationDate = '2020-04-04 11:51:00';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminArticlesCreatePage())
                ->type('@title', '')
                ->type('@publicationDate', $publicationDate)
                ->assertRadioSelected('@status', (string) ArticleStatus::published())
                ->type('@description', 'Description')
                ->type('@content', 'Content')
                ->click('@submit')
                ->click('@submit')
                ->assertRouteIs('admin.articles.create')
                ->assertSeeIn('@titleError', "The title field is required.")
                ->assertInputValue('@publicationDate', $publicationDate);
        });
    }
}
