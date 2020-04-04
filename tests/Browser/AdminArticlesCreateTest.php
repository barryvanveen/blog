<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\UserEloquentModel;
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

        $this->user = factory(UserEloquentModel::class)->create();
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
                ->assertSee('New article');
        });
    }

    // todo: test validation
}
