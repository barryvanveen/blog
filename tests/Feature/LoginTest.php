<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeLoginFormIfUnauthenticated()
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Login');
    }

    /** @test */
    public function loginWorksWithCorrectCredentials()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function loginFailsWithWrongCredentials()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->get(route('login'));

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'nope',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(route('login'));
    }


    /** @test */
    public function redirectIfAuthenticated()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        Auth::login($user);

        $response = $this->get(route('login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function logout()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        Auth::login($user);

        $response = $this->post(route('logout.post'));

        $response->assertRedirect(route('home'));
    }
}
