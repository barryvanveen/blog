<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\ArticleFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminArticlesEditPage;
use Tests\DuskTestCase;

class AdminArticlesEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @var UserEloquentModel */
    private $user;

    /** @var ArticleEloquentModel */
    private $article;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->article = ArticleFactory::new()->create();
    }

    /** @test */
    public function pageIsNotVisibleWithoutAuthentication(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new AdminArticlesEditPage($this->article->uuid))
                ->assertRouteIs('login');
        });
    }

    /** @test */
    public function editArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $newTitle = 'My new title';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminArticlesEditPage($this->article->uuid))
                ->assertRouteIs('admin.articles.edit', ['uuid' => $this->article->uuid])
                ->assertSee('Edit article')
                ->assertInputValue('@title', $this->article->title)
                ->type('@title', $newTitle)
                ->waitForCsrfToken()
                ->click('@submit')
                ->assertRouteIs('admin.articles.index')

                ->visit(new AdminArticlesEditPage($this->article->uuid))
                ->assertInputValue('@title', $newTitle);
        });
    }

    /** @test */
    public function editArticleValidation(): void
    {
        $this->browse(function (Browser $browser) {
            $publicationDate = '2020-04-04 11:51:00';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminArticlesEditPage($this->article->uuid))
                ->assertInputValue('@title', $this->article->title)
                ->type('@title', '')
                ->type('@publicationDate', $publicationDate)
                ->waitForCsrfToken()
                ->click('@submit')
                ->assertRouteIs('admin.articles.edit', ['uuid' => $this->article->uuid])
                ->assertSeeIn('@titleError', "The title field is required.")
                ->assertInputValue('@publicationDate', $publicationDate);
        });
    }
}
