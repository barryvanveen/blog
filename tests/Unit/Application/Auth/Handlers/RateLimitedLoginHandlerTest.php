<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Handlers\RateLimitedLoginHandler;
use App\Application\Auth\RateLimiterInterface;
use App\Domain\Core\CommandHandlerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * @covers \App\Application\Auth\Commands\Login
 * @covers \App\Application\Auth\Handlers\RateLimitedLoginHandler
 */
class RateLimitedLoginHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itLogsInIfNotAboveRateLimit()
    {
        // arrange
        $mockLoginHandler = $this->createMock(CommandHandlerInterface::class);

        $mockLoginHandler
            ->expects($this->once())
            ->method('handle');

        $mockRateLimiter = $this->createMock(RateLimiterInterface::class);

        $mockRateLimiter
            ->expects($this->once())
            ->method('tooManyAttempts')
            ->willReturn(false);

        $mockRateLimiter
            ->expects($this->once())
            ->method('clear');

        $command = new Login(
            'username',
            'secret',
            false,
            '123.123.123.123'
        );

        // act
        /** @var RateLimitedLoginHandler $handler */
        $handler = new RateLimitedLoginHandler($mockLoginHandler, $mockRateLimiter);
        $handler->handle($command);

        // assert done by mock expectations
    }

    /** @test */
    public function itLocksTheUserOutAfterTooManyAttempts()
    {
        // arrange
        $mockLoginHandler = $this->createMock(CommandHandlerInterface::class);

        $mockLoginHandler
            ->expects($this->never())
            ->method('handle');

        $mockRateLimiter = $this->createMock(RateLimiterInterface::class);

        $mockRateLimiter
            ->expects($this->once())
            ->method('tooManyAttempts')
            ->willReturn(true);

        $mockRateLimiter
            ->expects($this->once())
            ->method('availableIn')
            ->willReturn(3);

        $command = new Login(
            'username',
            'secret',
            false,
            '123.123.123.123'
        );

        // assert
        $this->expectException(ValidationException::class);

        // act
        /** @var RateLimitedLoginHandler $handler */
        $handler = new RateLimitedLoginHandler($mockLoginHandler, $mockRateLimiter);
        $handler->handle($command);
    }

    /** @test */
    public function itTracksFailedLoginAttemptsOnTheRateLimiter()
    {
        // arrange
        $mockLoginHandler = $this->createMock(CommandHandlerInterface::class);

        $mockLoginHandler
            ->expects($this->once())
            ->method('handle')
            ->willThrowException(ValidationException::withMessages([]));

        $mockRateLimiter = $this->createMock(RateLimiterInterface::class);

        $mockRateLimiter
            ->expects($this->once())
            ->method('tooManyAttempts')
            ->willReturn(false);

        $mockRateLimiter
            ->expects($this->once())
            ->method('hit');

        $command = new Login(
            'username',
            'wrongpassword',
            false,
            '123.123.123.123'
        );

        // assert
        $this->expectException(ValidationException::class);

        // act
        /** @var RateLimitedLoginHandler $handler */
        $handler = new RateLimitedLoginHandler($mockLoginHandler, $mockRateLimiter);
        $handler->handle($command);
    }
}
