<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AdminDashboardPage;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function logout(): void
    {
        /** @var UserEloquentModel $user */
        $user = UserFactory::new()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user->uuid)
                ->visit(new AdminDashboardPage())
                ->click('@logoutButton')

                ->assertRouteIs('home')
                ->assertGuest();
        });
    }
}
