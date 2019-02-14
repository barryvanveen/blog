<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Handlers\LoginHandler;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * @covers \App\Application\Auth\Commands\Login
 * @covers \App\Application\Auth\Handlers\LoginHandler
 */
class LoginHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itLogsTheUserIn()
    {
        // arrange
        /** @var User $user */
        $user = factory(User::class)->create();

        $command = new Login(
            $user->email,
            'secret',
            false,
            '123.123.123.123'
        );

        $this->assertGuest();

        // act
        /** @var LoginHandler $handler */
        $handler = app()->make(LoginHandler::class);
        $handler->handle($command);

        // assert
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function itFailsOnWrongCredentials()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $command = new Login(
            $user->email,
            'wrongpassword',
            false,
            '123.123.123.123'
        );

        // assert
        $this->expectException(ValidationException::class);

        // act
        /** @var LoginHandler $handler */
        $handler = app()->make(LoginHandler::class);
        $handler->handle($command);
    }
}
