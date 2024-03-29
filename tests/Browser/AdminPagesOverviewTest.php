<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\PageFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminPagesOverviewPage;
use Tests\DuskTestCase;

class AdminPagesOverviewTest extends DuskTestCase
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
                ->visit(new AdminPagesOverviewPage())
                ->assertRouteIs('login');
        });
    }

    /** @test */
    public function viewPages(): void
    {
        /** @var PageEloquentModel[] $pages */
        $pages = PageFactory::new()->count(3)->create();

        $this->browse(function (Browser $browser) use ($pages) {
            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminPagesOverviewPage())
                ->assertSeeIn('@title', 'Pages');

            foreach ($pages as $page) {
                $browser->assertSeeIn('@table', $page->title);
            }
        });
    }

    /** @test */
    public function editPage(): void
    {
        /** @var PageEloquentModel $page */
        $page = PageFactory::new()->create();

        $this->browse(function (Browser $browser) use ($page) {
            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminPagesOverviewPage())
                ->click('@editLink')
                ->assertRouteIs('admin.pages.edit', $page->slug);
        });
    }

    /** @test */
    public function createPage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminPagesOverviewPage())
                ->click('@createLink')
                ->assertRouteIs('admin.pages.create');
        });
    }
}
