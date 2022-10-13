<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Core\BaseEventListener;
use App\Application\Core\EventInterface;
use App\Application\Core\EventListenerInterface;
use App\Infrastructure\Adapters\LaravelEventBus;
use App\Infrastructure\Exceptions\LaravelEventBusException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use TypeError;

class FooEvent implements EventInterface
{
    public function __construct(public string $name)
    {
    }
}

class FooListener extends BaseEventListener
{
    private static array $calls = [];

    public function handleFooEvent(FooEvent $event): void
    {
        self::$calls[] = $event->name;
    }

    public function assertHandledEvent(FooEvent $event): bool
    {
        return in_array(
            $event->name,
            self::$calls
        );
    }
}

class BarListener extends BaseEventListener
{
    private static array $calls = [];

    public function handleFooEvent(FooEvent $event): void
    {
        self::$calls[] = $event->name;
    }

    public function assertHandledEvent(FooEvent $event): bool
    {
        return in_array(
            $event->name,
            self::$calls
        );
    }
}

class NoEvent
{
}

class NoListener
{
}

/**
 * @covers \App\Infrastructure\Adapters\LaravelEventBus
 * @covers \App\Infrastructure\Exceptions\LaravelEventBusException
 */
class LaravelEventBusTest extends TestCase
{
    /** @test */
    public function itDispatchesEvents(): void
    {
        // arrange
        /** @var Dispatcher $dispatcher */
        $dispatcher = app()->make(Dispatcher::class);
        $laravelEventBus = new LaravelEventBus($dispatcher);
        $laravelEventBus->subscribe(FooEvent::class, FooListener::class);

        // act
        $event = new FooEvent('asdasd');
        $laravelEventBus->dispatch($event);

        // assert
        $fooListener = new FooListener();
        $this->assertEquals(true, $fooListener->assertHandledEvent($event));

        $barListener = new BarListener();
        $this->assertEquals(false, $barListener->assertHandledEvent($event));
    }

    /** @test */
    public function itDispatchesEventsToMultipleListeners(): void
    {
        // arrange
        /** @var Dispatcher $dispatcher */
        $dispatcher = app()->make(Dispatcher::class);
        $laravelEventBus = new LaravelEventBus($dispatcher);
        $laravelEventBus->subscribe(FooEvent::class, FooListener::class);
        $laravelEventBus->subscribe(FooEvent::class, BarListener::class);

        // act
        $event = new FooEvent('asdasd');
        $laravelEventBus->dispatch($event);

        // assert
        $fooListener = new FooListener();
        $this->assertEquals(true, $fooListener->assertHandledEvent($event));

        $barListener = new BarListener();
        $this->assertEquals(true, $barListener->assertHandledEvent($event));
    }

    /** @test */
    public function itThrowsAnExceptionWhenDispatchingAWrongEvent(): void
    {
        // arrange
        $laravelEventBus = new LaravelEventBus(Event::fake());

        // assert
        $this->expectException(TypeError::class);

        // act
        $laravelEventBus->dispatch(new NoEvent());
    }

    /** @test */
    public function itThrowsAnExceptionWhenSubscribingAWrongEvent(): void
    {
        // arrange
        $laravelEventBus = new LaravelEventBus(Event::fake());
        $eventClassName = NoEvent::class;
        $interfaceClassName = EventInterface::class;

        // assert
        $this->expectException(LaravelEventBusException::class);
        $this->expectExceptionMessage("Event ${eventClassName} does not implement ${interfaceClassName}");

        // act
        $laravelEventBus->subscribe(NoEvent::class, FooListener::class);
    }

    /** @test */
    public function itThrowsAnExceptionWhenSubscribingAWrongListener(): void
    {
        // arrange
        $laravelEventBus = new LaravelEventBus(Event::fake());
        $listenerClassName = NoListener::class;
        $interfaceClassName = EventListenerInterface::class;

        // assert
        $this->expectException(LaravelEventBusException::class);
        $this->expectExceptionMessage("Listener ${listenerClassName} does not implement ${interfaceClassName}");

        // act
        $laravelEventBus->subscribe(FooEvent::class, NoListener::class);
    }
}
