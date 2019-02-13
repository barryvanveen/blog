<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function login()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

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
    public function loginFailsWithoutCsrfToken()
    {
        /** @var User $user2 */
        $user2 = factory(User::class)->create();

        Browser::macro('clearCsrfInputs', function () {
            $this->script("$(\"input[type='hidden'][name=_token]\").each(function(){ this.value = '' });");

            return $this;
        });

        $this->browse(function (Browser $browser) use ($user2) {
            $browser
                ->logout() // reset any previous sessions
                ->visit(new LoginPage())
                ->type('email', $user2->email)
                ->type('password', 'secret')
                ->clearCsrfInputs()

                ->press('Login')

                ->assertRouteIs('login')
                ->assertSee('419')
                ->assertGuest();
        });
    }

    /** @test */
    public function failedLoginShowsErrorMessages()
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
