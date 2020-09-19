<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelGuard;
use App\Infrastructure\Eloquent\UserEloquentModel;
use App\Infrastructure\Exceptions\InvalidGuardException;
use Auth;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\UnauthorizedException;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;
use stdClass;

/**
 * @covers \App\Infrastructure\Adapters\LaravelGuard
 * @covers \App\Infrastructure\Exceptions\InvalidGuardException
 */
class LaravelGuardTest extends TestCase
{
    use RefreshDatabase;

    /** @var LaravelGuard */
    private $guard;

    /** @var UserEloquentModel */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create([
            'email' => 'foo@bar.baz',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);

        /** @var Factory $authFactory */
        $authFactory = $this->app->make(Factory::class);

        $this->guard = new LaravelGuard($authFactory);
    }

    /** @test */
    public function itThrowsAndExceptionWhenGivenAnIncorrectGuard(): void
    {
        /** @var ObjectProphecy|Factory $authFactory */
        $authFactory = $this->prophesize(Factory::class);
        $authFactory->guard()->willReturn(new StdClass());

        $this->expectException(InvalidGuardException::class);

        new LaravelGuard($authFactory->reveal());
    }

    /** @test */
    public function itPassesAuthentication(): void
    {
        $this->assertFalse(Auth::check());

        $this->assertTrue(
            $this->guard->attempt($this->user->email, 'secret')
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
            $this->guard->attempt($this->user->email, 'Secr3t')
        );

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function itLogsAnAuthenticatedUserOut(): void
    {
        Auth::login($this->user);

        $this->assertTrue(Auth::check());

        $this->guard->logout();

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function itReturnsTheAuthenticatedUser(): void
    {
        Auth::login($this->user);

        $result = $this->guard->user();

        $this->assertEquals($this->user->email, $result->email());
        $this->assertEquals($this->user->name, $result->name());
        $this->assertEquals($this->user->uuid, $result->uuid());
    }

    /** @test */
    public function itReturnsTrueIfUserIsAuthenticated(): void
    {
        Auth::login($this->user);

        $this->assertEquals(true, $this->guard->authenticated());
    }

    /** @test */
    public function itReturnsFalseIfUserIsNotAuthenticated(): void
    {
        $this->assertEquals(false, $this->guard->authenticated());
    }

    /** @test */
    public function itThrowsAnErrorIfUnauthenticated(): void
    {
        $this->expectException(UnauthorizedException::class);

        $this->guard->user();
    }
}
