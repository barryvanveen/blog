<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function login(): void
    {
        /** @var UserEloquentModel $user */
        $user = UserFactory::new()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->logout() // reset any previous sessions
                ->visit(new LoginPage())
                ->type('email', $user->email)
                ->type('password', 'secret')

                ->press('Login')

                ->assertRouteIs('admin.dashboard')
                ->assertAuthenticatedAs($user);
        });
    }

    /** @test */
    public function loginFailsWithoutCsrfToken(): void
    {
        /** @var UserEloquentModel $user2 */
        $user2 = UserFactory::new()->create();

        Browser::macro('removeCsrfInput', function () {
            $this->script("var form = document.forms.login; var token = form.elements._token; token.remove();");

            return $this;
        });

        $this->browse(function (Browser $browser) use ($user2) {
            $browser
                ->logout() // reset any previous sessions
                ->visit(new LoginPage())
                ->type('email', $user2->email)
                ->type('password', 'secret')
                ->removeCsrfInput()

                ->press('Login')

                ->assertRouteIs('login')
                ->assertSee('419')
                ->assertGuest();
        });
    }

    /** @test */
    public function failedLoginShowsErrorMessages(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->logout() // reset any previous sessions
                ->visit(new LoginPage())
                ->type('email', 'nonexistent@example.com')
                ->type('password', 'secret')

                ->press('Login')

                ->assertRouteIs('login')
                ->assertSee(__('auth.failed'))
                ->assertGuest();
        });
    }
}
