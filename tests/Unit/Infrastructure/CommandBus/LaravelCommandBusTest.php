<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\CommandBus;

use App\Application\Core\BaseCommandHandler;
use App\Domain\Core\CommandInterface;
use App\Infrastructure\CommandBus\LaravelCommandBus;
use App\Infrastructure\CommandBus\LaravelCommandBusException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Testing\Fakes\BusFake;
use Tests\TestCase;

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
    public function handleFooCommand(FooCommand $command)
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
 * @covers \App\Infrastructure\CommandBus\LaravelCommandBus
 * @covers \App\Infrastructure\CommandBus\LaravelCommandBusException
 */
class LaravelCommandBusTest extends TestCase
{
    /** @test */
    public function itDispatchesCommands()
    {
        // arrange
        $fakeBus = new BusFake();
        Bus::swap($fakeBus);

        $laravelCommandBus = new LaravelCommandBus($fakeBus, app());
        $laravelCommandBus->subscribe(FooCommand::class, FooHandler::class);

        // act
        $laravelCommandBus->dispatch(new FooCommand('asdasd'));

        // assert
        Bus::assertDispatched(FooCommand::class, function (FooCommand $command) {
            return 'asdasd' === $command->name;
        });
    }

    /** @test */
    public function itThrowsAnExceptionWhenNoHandlerIsSubscribed()
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(new BusFake(), app());

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage('No handler found for Tests\Unit\Infrastructure\CommandBus\FooCommand');

        // act
        $laravelCommandBus->dispatch(new FooCommand('asdasd'));
    }

    /** @test */
    public function itThrowsAnExceptionWhenDispatchingAWrongCommand()
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(new BusFake(), app());

        // assert
        $this->expectException(\TypeError::class);

        // act
        $laravelCommandBus->dispatch(new NoCommand());
    }

    /** @test */
    public function itThrowsAnExceptionWhenSubscribingAWrongCommand()
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(new BusFake(), app());

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage('Command Tests\Unit\Infrastructure\CommandBus\NoCommand does not implement App\Domain\Core\CommandInterface');

        // act
        $laravelCommandBus->subscribe(NoCommand::class, FooHandler::class);
    }

    /** @test */
    public function itThrowsAnExceptionWhenSubscribingAWrongHandler()
    {
        // arrange
        $laravelCommandBus = new LaravelCommandBus(new BusFake(), app());

        // assert
        $this->expectException(LaravelCommandBusException::class);
        $this->expectExceptionMessage('Handler Tests\Unit\Infrastructure\CommandBus\NoHandler does not implement App\Domain\Core\CommandHandlerInterface');

        // act
        $laravelCommandBus->subscribe(FooCommand::class, NoHandler::class);
    }
}
