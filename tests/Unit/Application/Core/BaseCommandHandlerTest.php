<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Core;

use App\Application\Core\BaseCommandHandler;
use App\Application\Core\CommandHandlerException;
use App\Application\Core\CommandInterface;
use Tests\TestCase;

class DummyCommand implements CommandInterface
{
}

class FooCommand implements CommandInterface
{
}

class DummyCommandHandler extends BaseCommandHandler
{
    public $called = false;

    public function handleDummyCommand(): void
    {
        $this->called = true;
    }
}

/**
 * @covers \App\Application\Core\BaseCommandHandler
 * @covers \App\Application\Core\CommandHandlerException
 */
class BaseCommandHandlerTest extends TestCase
{
    /** @test */
    public function itCallsTheAppropriateHandleMethod(): void
    {
        // arrange
        $handler = new DummyCommandHandler();
        $this->assertFalse($handler->called);

        // act
        $handler->handle(new DummyCommand());

        // assert
        $this->assertTrue($handler->called);
    }

    /** @test */
    public function itThrowsAnExceptionWhenTheHandleMethodIsMissing(): void
    {
        // arrange
        $handler = new DummyCommandHandler();

        $this->expectException(CommandHandlerException::class);
        $this->expectExceptionMessage('CommandHandler should include method handleFooCommand');

        // act
        $handler->handle(new FooCommand());
    }
}
