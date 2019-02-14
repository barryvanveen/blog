<?php

declare(strict_types=1);

namespace Tests\Unit\Articles\Handlers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\Handlers\CreateArticleHandler;
use App\Domain\Articles\Enums\ArticleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\Handlers\CreateArticleHandler
 */
class CreateArticleHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCreatesAnArticle()
    {
        // arrange
        $command = new CreateArticle(
            1,
            'baz',
            'bar',
            now(),
            ArticleStatus::PUBLISHED(),
            'Foo title'
        );

        // act
        /** @var CreateArticleHandler $handler */
        $handler = app()->make(CreateArticleHandler::class);
        $handler->handle($command);

        // assert
        $this->assertDatabaseHas('articles', ['title' => 'Foo title']);
    }
}
