<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeLoginFormIfUnauthenticated(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Login');
    }

    /** @test */
    public function loginWorksWithCorrectCredentials(): void
    {
        /** @var UserEloquentModel $user */
        $user = UserFactory::new()->create();

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function loginFailsWithWrongCredentials(): void
    {
        /** @var UserEloquentModel $user */
        $user = UserFactory::new()->create();

        $this->get(route('login'));

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'nope',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(route('login'));
    }


    /** @test */
    public function redirectIfAuthenticated(): void
    {
        /** @var UserEloquentModel $user */
        $user = UserFactory::new()->create();

        Auth::login($user);

        $response = $this->get(route('login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function logout(): void
    {
        /** @var UserEloquentModel $user */
        $user = UserFactory::new()->create();

        Auth::login($user);

        $response = $this->post(route('logout.post'));

        $response->assertRedirect(route('home'));
    }
}
