<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminPagesCreatePage;
use Tests\DuskTestCase;

class AdminPagesCreateTest extends DuskTestCase
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
                ->visit(new AdminPagesCreatePage())
                ->assertRouteIs('login');
        });
    }

    /** @test */
    public function createPage(): void
    {
        $this->browse(function (Browser $browser) {
            $title = 'My new title';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminPagesCreatePage())
                ->assertRouteIs('admin.pages.create')
                ->assertSee('New page')
                ->type('@title', $title)
                ->type('@slug', 'my-slug')
                ->type('@description', 'Description')
                ->type('@content', 'Content')
                ->click('@submit')
                ->assertRouteIs('admin.pages.index')
                ->assertSee($title);
        });
    }

    /** @test */
    public function createPageValidation(): void
    {
        $this->browse(function (Browser $browser) {
            $slug = 'my-slug-string';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminPagesCreatePage())
                ->type('@title', '')
                ->type('@slug', $slug)
                ->type('@description', 'Description')
                ->type('@content', 'Content')
                ->click('@submit')
                ->assertRouteIs('admin.pages.create')
                ->assertSeeIn('@titleError', "The title field is required.")
                ->assertInputValue('@slug', $slug);
        });
    }
}
