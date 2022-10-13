<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Auth\Exceptions\LockoutException;
use App\Application\Auth\Handlers\RateLimitedLoginHandler;
use App\Application\Core\CommandHandlerInterface;
use App\Application\Interfaces\RateLimiterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @covers \App\Application\Auth\Commands\Login
 * @covers \App\Application\Auth\Handlers\RateLimitedLoginHandler
 * @covers \App\Application\Auth\Exceptions\FailedLoginException
 * @covers \App\Application\Auth\Exceptions\LockoutException
 */
class RateLimitedLoginHandlerTest extends TestCase
{
    private CommandHandlerInterface|MockObject $mockLoginHandler;

    private RateLimiterInterface|MockObject $mockRateLimiter;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockLoginHandler = $this->createMock(CommandHandlerInterface::class);

        $this->mockRateLimiter = $this->createMock(RateLimiterInterface::class);
    }

    /** @test */
    public function itLogsInIfNotAboveRateLimit(): void
    {
        // arrange
        $this->mockLoginHandler
            ->expects($this->once())
            ->method('handle');

        $this->mockRateLimiter
            ->expects($this->once())
            ->method('tooManyAttempts')
            ->willReturn(false);

        $this->mockRateLimiter
            ->expects($this->once())
            ->method('clear');

        $command = new Login(
            'username',
            'secret',
            false,
            '123.123.123.123'
        );

        // act
        $handler = new RateLimitedLoginHandler(
            $this->mockLoginHandler,
            $this->mockRateLimiter
        );
        $handler->handle($command);

        // assert done by mock expectations
    }

    /** @test */
    public function itLocksTheUserOutAfterTooManyAttempts(): void
    {
        // arrange
        $this->mockLoginHandler
            ->expects($this->never())
            ->method('handle');

        $this->mockRateLimiter
            ->expects($this->once())
            ->method('tooManyAttempts')
            ->willReturn(true);

        $this->mockRateLimiter
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
        $this->expectException(LockoutException::class);

        // act
        $handler = new RateLimitedLoginHandler(
            $this->mockLoginHandler,
            $this->mockRateLimiter
        );
        $handler->handle($command);
    }

    /** @test */
    public function itTracksFailedLoginAttemptsOnTheRateLimiter(): void
    {
        // arrange
        $this->mockLoginHandler
            ->expects($this->once())
            ->method('handle')
            ->willThrowException(FailedLoginException::credentialsDontMatch());

        $this->mockRateLimiter
            ->expects($this->once())
            ->method('tooManyAttempts')
            ->willReturn(false);

        $this->mockRateLimiter
            ->expects($this->once())
            ->method('hit');

        $command = new Login(
            'username',
            'wrongpassword',
            false,
            '123.123.123.123'
        );

        // assert
        $this->expectException(FailedLoginException::class);

        // act
        $handler = new RateLimitedLoginHandler(
            $this->mockLoginHandler,
            $this->mockRateLimiter
        );
        $handler->handle($command);
    }
}
