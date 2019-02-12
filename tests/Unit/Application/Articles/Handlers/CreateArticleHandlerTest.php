<?php

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
        $input = [
            'author_id' => 1,
            'content' => 'baz',
            'description' => 'bar',
            'published_at' => now(),
            'status' => ArticleStatus::PUBLISHED(),
            'title' => 'Foo title',
        ];

        // act
        /** @var CreateArticleHandler $handler */
        $handler = app()->make(CreateArticleHandler::class);
        $handler->handle(new CreateArticle($input));

        // assert
        $this->assertDatabaseHas('articles', ['title' => 'Foo title']);
    }
}
