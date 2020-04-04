<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function redirectGuestsToLoginPage(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function displaysDashboard(): void
    {
        /** @var UserEloquentModel $user */
        $user = factory(UserEloquentModel::class)->create();
        Auth::login($user);

        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee($user->name);
    }
}
