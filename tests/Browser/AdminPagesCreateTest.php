<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminPagesCreatePage;
use Tests\DuskTestCase;

class AdminPagesCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    private UserEloquentModel $user;

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
                ->waitForCsrfToken()
                ->clickAndWaitForReload('@submit')
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
                ->waitForCsrfToken()
                ->waitForPreviewRendered('description')
                ->waitForPreviewRendered('content')
                ->clickAndWaitForReload('@submit')
                ->assertRouteIs('admin.pages.create')
                ->assertSeeIn('@titleError', "The title field is required.")
                ->assertInputValue('@slug', $slug);
        });
    }
}
