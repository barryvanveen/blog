<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments\Handlers;

use App\Application\Comments\Commands\CreateComment;
use App\Application\Comments\Handlers\CreateCommentHandler;
use App\Domain\Comments\CommentStatus;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\Commands\CreateComment
 * @covers \App\Application\Comments\Handlers\CreateCommentHandler
 */
class CreateCommentHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCreatesAComment(): void
    {
        // arrange
        $command = new CreateComment(
            'article-foo',
            'My comment',
            new DateTimeImmutable(),
            'foo@bar.tld',
            '123.123.123.123',
            'My name',
            CommentStatus::published()
        );

        // act
        /** @var CreateCommentHandler $handler */
        $handler = app()->make(CreateCommentHandler::class);
        $handler->handle($command);

        // assert
        $this->assertDatabaseHas('comments', ['article_uuid' => 'article-foo', 'name' => 'My name']);
    }
}
