<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments\Handlers;

use App\Application\Comments\Commands\UpdateComment;
use App\Application\Comments\Handlers\UpdateCommentHandler;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use Database\Factories\ArticleFactory;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\Commands\UpdateComment
 * @covers \App\Application\Comments\Handlers\UpdateCommentHandler
 */
class UpdateCommentHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itUpdatesAComment(): void
    {
        // arrange
        /** @var ArticleEloquentModel $article */
        $article = ArticleFactory::new()->create();

        /** @var CommentEloquentModel $comment */
        $comment = CommentFactory::new()->create([
            'article_uuid' => $article->uuid,
            'name' => 'My Old Name',
        ]);

        $command = new UpdateComment(
            $comment->article_uuid,
            $comment->content,
            $comment->created_at->toDateTimeImmutable(),
            $comment->email,
            $comment->ip,
            'My New Name',
            $comment->status,
            $comment->uuid
        );

        // act
        /** @var UpdateCommentHandler $handler */
        $handler = app()->make(UpdateCommentHandler::class);
        $handler->handle($command);

        // assert
        $this->assertDatabaseHas('comments', ['uuid' => $comment->uuid, 'name' => 'My New Name']);
    }
}
