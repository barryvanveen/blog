<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\Handlers;

use App\Application\Pages\Commands\CreatePage;
use App\Application\Pages\Handlers\CreatePageHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\Commands\CreatePage
 * @covers \App\Application\Pages\Handlers\CreatePageHandler
 */
class CreatePageHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCreatesAPage(): void
    {
        // arrange
        $command = new CreatePage(
            'baz',
            'bar',
            'foo-title',
            'Foo title'
        );

        // act
        /** @var CreatePageHandler $handler */
        $handler = app()->make(CreatePageHandler::class);
        $handler->handle($command);

        // assert
        $this->assertDatabaseHas('pages', ['title' => 'Foo title']);
    }
}
