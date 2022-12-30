<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Core\BaseCommandHandler;
use App\Application\Core\CommandHandlerInterface;
use App\Application\Core\CommandInterface;
use App\Infrastructure\Adapters\LaravelCommandBus;
use App\Infrastructure\Exceptions\LaravelCommandBusException;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use TypeError;

class FooCommand implements CommandInterface
{
    public function __construct(
        public string $name,
    ) {
    }
}

class FooHandler extends BaseCommandHandler
{
    public function handleFooCommand(FooCommand $command): void
    {
    }
}

class NoCommand
{
}

class NoHandler
{
}

/**
 * @covers \App\Infrastructure\Adapters\LaravelCommandBus
 * @covers \App\Infrastructure\Exceptions\LaravelCommandBusException
 */
class LaravelCommandBusTest extends TestCase
{
    /** @test */
    public function itDispatchesCommands(): void
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(Bus::fake());
        $laravelCommandBus->subscribe(FooCommand::class, FooHandler::class);

        // act
        $laravelCommandBus->dispatch(new FooCommand('asdasd'));

        // assert
        Bus::assertDispatched(
            FooCommand::class,
            fn(FooCommand $command) => 'asdasd' === $command->name
        );
    }

    /** @test */
    public function itThrowsAnExceptionWhenNoHandlerIsSubscribed(): void
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(Bus::fake());
        $command = new FooCommand('asdasd');
        $commandClassName = $command::class;

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage("No handler found for {$commandClassName}");

        // act
        $laravelCommandBus->dispatch($command);
    }

    /** @test */
    public function itThrowsAnExceptionWhenDispatchingAWrongCommand(): void
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(Bus::fake());

        // assert
        $this->expectException(TypeError::class);

        // act
        $laravelCommandBus->dispatch(new NoCommand());
    }

    /** @test */
    public function itThrowsAnExceptionWhenSubscribingAWrongCommand(): void
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(Bus::fake());
        $commandClassName = NoCommand::class;
        $interfaceClassName = CommandInterface::class;

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage("Command {$commandClassName} does not implement {$interfaceClassName}");

        // act
        $laravelCommandBus->subscribe(NoCommand::class, FooHandler::class);
    }

    /** @test */
    public function itThrowsAnExceptionWhenSubscribingAWrongHandler(): void
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(Bus::fake());
        $handlerClassName = NoHandler::class;
        $interfaceClassName = CommandHandlerInterface::class;

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage("Handler {$handlerClassName} does not implement {$interfaceClassName}");

        // act
        $laravelCommandBus->subscribe(FooCommand::class, NoHandler::class);
    }
}
