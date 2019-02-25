<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Auth\Handlers;

use App\Application\Auth\Commands\Logout;
use App\Application\Auth\Handlers\LogoutHandler;
use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\SessionInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @covers \App\Application\Auth\Commands\Logout
 * @covers \App\Application\Auth\Handlers\LogoutHandler
 */
class LogoutHandlerTest extends TestCase
{
    /** @test */
    public function itLogsTheUserOut(): void
    {
        // arrange
        /** @var GuardInterface|MockObject $guardMock */
        $guardMock = $this->createMock(GuardInterface::class);

        /** @var \App\Application\Interfaces\SessionInterface|MockObject $sessionMock */
        $sessionMock = $this->createMock(SessionInterface::class);

        $guardMock->expects($this->once())
            ->method('logout');

        $sessionMock->expects($this->once())
            ->method('invalidate');

        $command = new Logout();

        // act
        $handler = new LogoutHandler($guardMock, $sessionMock);
        $handler->handle($command);

        // assertions by mock expections
    }
}
