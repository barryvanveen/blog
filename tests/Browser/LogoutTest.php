<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminDashboardPage;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function login()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user->id)
                ->visit(new AdminDashboardPage())
                ->click('@logoutButton')

                ->assertRouteIs('home')
                ->assertGuest();
        });
    }
}
