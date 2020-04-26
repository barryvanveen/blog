<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Core;

use App\Application\Core\BaseEventListener;
use App\Application\Core\EventInterface;
use App\Application\Exceptions\EventListenerException;
use Tests\TestCase;

class DummyEvent implements EventInterface
{
}

class FooEvent implements EventInterface
{
}

class DummyEventListener extends BaseEventListener
{
    public $called = false;

    public function handleDummyEvent(): void
    {
        $this->called = true;
    }
}

/**
 * @covers \App\Application\Core\BaseEventListener
 * @covers \App\Application\Exceptions\EventListenerException
 */
class BaseEventListenerTest extends TestCase
{
    /** @test */
    public function itCallsTheAppropriateHandleMethod(): void
    {
        // arrange
        $handler = new DummyEventListener();
        $this->assertFalse($handler->called);

        // act
        $handler->handle(new DummyEvent());

        // assert
        $this->assertTrue($handler->called);
    }

    /** @test */
    public function itThrowsAnExceptionWhenTheHandleMethodIsMissing(): void
    {
        // arrange
        $handler = new DummyEventListener();

        $this->expectException(EventListenerException::class);
        $this->expectExceptionMessage('EventListener should include method handleFooEvent');

        // act
        $handler->handle(new FooEvent());
    }
}
