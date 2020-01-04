<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelGuard;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Auth;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelGuard
 */
class LaravelGuardTest extends TestCase
{
    use RefreshDatabase;

    /** @var LaravelGuard */
    private $guard;

    public function setUp(): void
    {
        parent::setUp();

        factory(UserEloquentModel::class)->create([
            'email' => 'foo@bar.baz',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);

        /** @var Factory $authFactory */
        $authFactory = $this->app->make(Factory::class);

        $this->guard = new LaravelGuard($authFactory);
    }

    /** @test */
    public function itPassesAuthentication(): void
    {
        $this->assertFalse(Auth::check());

        $this->assertTrue(
            $this->guard->attempt('foo@bar.baz', 'secret')
        );

        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function itFailsToAuthenticateWithWrongEmail(): void
    {
        $this->assertFalse(Auth::check());

        $this->assertFalse(
            $this->guard->attempt('notCorrect@bar.baz', 'secret')
        );

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function itFailsToAuthenticateWithWrongPassword(): void
    {
        $this->assertFalse(Auth::check());

        $this->assertFalse(
            $this->guard->attempt('foo@bar.baz', 'Secr3t')
        );

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function itLogsAnAuthenticatedUserOut(): void
    {
        $this->guard->attempt('foo@bar.baz', 'secret');

        $this->assertTrue(Auth::check());

        $this->guard->logout();

        $this->assertFalse(Auth::check());
    }
}
