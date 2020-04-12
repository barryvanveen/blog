<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Auth\Handlers\LoginHandler;
use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\SessionInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @covers \App\Application\Auth\Commands\Login
 * @covers \App\Application\Auth\Handlers\LoginHandler
 * @covers \App\Application\Auth\Exceptions\FailedLoginException
 */
class LoginHandlerTest extends TestCase
{
    /** @var GuardInterface|MockObject */
    protected $guardMock;

    /** @var SessionInterface|MockObject */
    private $sessionMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->guardMock = $this->createMock(GuardInterface::class);

        $this->sessionMock = $this->createMock(SessionInterface::class);
    }

    /** @test */
    public function itLogsTheUserIn(): void
    {
        // arrange
        $this->guardMock->expects($this->once())
            ->method('attempt')
            ->willReturn(true);

        $this->sessionMock->expects($this->once())
            ->method('regenerate');

        $command = new Login(
            'email@domain.tld',
            'secret',
            false,
            '123.123.123.123'
        );

        // act
        $handler = new LoginHandler($this->guardMock, $this->sessionMock);
        $handler->handle($command);

        // assert by mock expectations
    }

    /** @test */
    public function itFailsOnWrongCredentials(): void
    {
        // arrange
        $this->guardMock->expects($this->once())
            ->method('attempt')
            ->willReturn(false);

        $this->sessionMock->expects($this->never())
            ->method('regenerate');

        $command = new Login(
            'email@domain.tld',
            'secret',
            false,
            '123.123.123.123'
        );

        // assert
        $this->expectException(FailedLoginException::class);

        // act
        $handler = new LoginHandler($this->guardMock, $this->sessionMock);
        $handler->handle($command);
    }
}
