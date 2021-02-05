<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\PageFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminPagesEditPage;
use Tests\DuskTestCase;

class AdminPageEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @var UserEloquentModel */
    private $user;

    /** @var PageEloquentModel */
    private $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->page = PageFactory::new()->create([
            'slug' => 'about',
        ]);
    }

    /** @test */
    public function pageIsNotVisibleWithoutAuthentication(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new AdminPagesEditPage($this->page->slug))
                ->assertRouteIs('login');
        });
    }

    /** @test */
    public function editPage(): void
    {
        $this->browse(function (Browser $browser) {
            $newTitle = 'My new title';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminPagesEditPage($this->page->slug))
                ->assertRouteIs('admin.pages.edit', ['slug' => $this->page->slug])
                ->assertSee('Edit page')
                ->assertInputValue('@title', $this->page->title)
                ->type('@title', $newTitle)
                ->waitForCsrfToken()
                ->click('@submit')
                ->assertRouteIs('admin.pages.index')

                ->visit(new AdminPagesEditPage($this->page->slug))
                ->assertInputValue('@title', $newTitle);
        });
    }

    /** @test */
    public function editPageValidation(): void
    {
        $this->browse(function (Browser $browser) {
            $content = 'my old content';

            $browser
                ->loginAs($this->user->uuid)
                ->visit(new AdminPagesEditPage($this->page->slug))
                ->assertInputValue('@title', $this->page->title)
                ->type('@title', '')
                ->type('@content', $content)
                ->waitForCsrfToken()
                ->click('@submit')
                ->assertRouteIs('admin.pages.edit', ['slug' => $this->page->slug])
                ->assertSeeIn('@titleError', "The title field is required.")
                ->assertInputValue('@content', $content);
        });
    }
}
