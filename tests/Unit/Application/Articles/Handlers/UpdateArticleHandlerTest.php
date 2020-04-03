<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\Handlers;

use App\Application\Articles\Commands\UpdateArticle;
use App\Application\Articles\Handlers\UpdateArticleHandler;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\Commands\UpdateArticle
 * @covers \App\Application\Articles\Handlers\UpdateArticleHandler
 */
class UpdateArticleHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCreatesAnArticle(): void
    {
        // arrange
        /** @var ArticleEloquentModel $article */
        $article = factory(ArticleEloquentModel::class)->create([
            'status' => ArticleStatus::unpublished(),
            'title' => 'My title',
        ]);

        $newTitle = 'My new title';
        $newStatus = ArticleStatus::published();

        $command = new UpdateArticle(
            $article->content,
            $article->description,
            DateTimeImmutable::createFromMutable($article->published_at),
            $newStatus,
            $newTitle,
            $article->uuid
        );

        // act
        /** @var UpdateArticleHandler $handler */
        $handler = $this->app->make(UpdateArticleHandler::class);

        $handler->handleUpdateArticle($command);

        // assert
        $this->assertDatabaseHas('articles', [
            'title' => $newTitle,
            'status' => (string) $newStatus,
            'uuid' => $article->uuid,
        ]);
    }
}
