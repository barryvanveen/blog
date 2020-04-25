<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Core\BaseCommandHandler;
use App\Application\Core\CommandInterface;
use App\Infrastructure\Adapters\LaravelCommandBus;
use App\Infrastructure\Exceptions\LaravelCommandBusException;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use TypeError;

class FooCommand implements CommandInterface
{
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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
            function (FooCommand $command) {
                return 'asdasd' === $command->name;
            }
        );
    }

    /** @test */
    public function itThrowsAnExceptionWhenNoHandlerIsSubscribed(): void
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(Bus::fake());

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage('No handler found for Tests\Unit\Infrastructure\CommandBus\FooCommand');

        // act
        $laravelCommandBus->dispatch(new FooCommand('asdasd'));
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

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage(
            'Command Tests\Unit\Infrastructure\CommandBus\NoCommand does not implement App\Application\Core\CommandInterface'
        );

        // act
        $laravelCommandBus->subscribe(NoCommand::class, FooHandler::class);
    }

    /** @test */
    public function itThrowsAnExceptionWhenSubscribingAWrongHandler(): void
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(Bus::fake());

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage(
            'Handler Tests\Unit\Infrastructure\CommandBus\NoHandler does not implement App\Application\Core\CommandHandlerInterface'
        );

        // act
        $laravelCommandBus->subscribe(FooCommand::class, NoHandler::class);
    }
}
