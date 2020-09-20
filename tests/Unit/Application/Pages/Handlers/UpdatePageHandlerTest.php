<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\Handlers;

use App\Application\Pages\Commands\UpdatePage;
use App\Application\Pages\Handlers\UpdatePageHandler;
use App\Infrastructure\Eloquent\PageEloquentModel;
use Database\Factories\PageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\Commands\UpdatePage
 * @covers \App\Application\Pages\Handlers\UpdatePageHandler
 */
class UpdatePageHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itUpdatesAPage(): void
    {
        // arrange
        /** @var PageEloquentModel $page */
        $page = PageFactory::new()->create([
            'slug' => 'about',
            'title' => 'my old title',
        ]);

        $newTitle = 'my new title';

        $command = new UpdatePage(
            $page->content,
            $page->description,
            $page->slug,
            $newTitle
        );

        // act
        /** @var UpdatePageHandler $handler */
        $handler = app()->make(UpdatePageHandler::class);
        $handler->handle($command);

        // assert
        $this->assertDatabaseHas('pages', ['title' => $newTitle]);
    }
}
