<?php

declare(strict_types=1);

namespace Tests\Unit\Articles;

use App\Application\Articles\ArticleRepository;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\ArticleRepository
 */
class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itRetrievesOnlyPublishedArticles()
    {
        // arrange
        $repository = new ArticleRepository();
        $dateInPast = Carbon::now()->subDay();
        $dateInFuture = Carbon::now()->addDay();
        factory(Article::class)->create(['published_at' => $dateInPast, 'status' => ArticleStatus::PUBLISHED(), 'title' => 'article1']);
        factory(Article::class)->create(['published_at' => $dateInPast, 'status' => ArticleStatus::UNPUBLISHED(), 'title' => 'article2']);
        factory(Article::class)->create(['published_at' => $dateInFuture, 'status' => ArticleStatus::PUBLISHED(), 'title' => 'article3']);
        factory(Article::class)->create(['published_at' => $dateInFuture, 'status' => ArticleStatus::UNPUBLISHED(), 'title' => 'article4']);

        // act
        $articles = $repository->allPublishedAndOrdered();

        // assert
        $this->assertCount(1, $articles);
        $this->assertEquals('article1', $articles[0]->title);
    }

    /** @test */
    public function itRetrievesPublishedArticlesInTheCorrectOrder()
    {
        // arrange
        $repository = new ArticleRepository();
        $yesterday = Carbon::now()->subDay();
        $lastWeek = Carbon::now()->subWeek();
        $lastYear = Carbon::now()->subYear();

        factory(Article::class)->create(['published_at' => $lastWeek, 'status' => ArticleStatus::PUBLISHED(), 'title' => 'article2']);
        factory(Article::class)->create(['published_at' => $lastYear, 'status' => ArticleStatus::PUBLISHED(), 'title' => 'article3']);
        factory(Article::class)->create(['published_at' => $yesterday, 'status' => ArticleStatus::PUBLISHED(), 'title' => 'article1']);

        // act
        $articles = $repository->allPublishedAndOrdered();

        // assert
        $this->assertCount(3, $articles);
        $this->assertEquals('article1', $articles[0]->title);
        $this->assertEquals('article2', $articles[1]->title);
        $this->assertEquals('article3', $articles[2]->title);
    }

    /** @test */
    public function itSavesAnArticle()
    {
        // arrange
        $article = factory(Article::class)->make([
            'title' => 'Foo Article Title',
        ]);
        $repository = new ArticleRepository();

        // act
        $repository->save($article);

        // assert
        $this->assertDatabaseHas('articles', ['title' => 'Foo Article Title']);
        // todo: assert event was raised
    }
}
