<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Auth\Handlers;

use App\Application\Auth\Commands\Logout;
use App\Application\Auth\Handlers\LogoutHandler;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * @covers \App\Application\Auth\Commands\Logout
 * @covers \App\Application\Auth\Handlers\LogoutHandler
 */
class LogoutHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itLogsTheUserOut()
    {
        // arrange
        /** @var User $user */
        $user = factory(User::class)->create();
        Auth::login($user);

        $command = new Logout();

        $this->assertAuthenticatedAs($user);

        // act
        /** @var LogoutHandler $handler */
        $handler = app()->make(LogoutHandler::class);
        $handler->handle($command);

        // assert
        $this->assertGuest();
    }
}
