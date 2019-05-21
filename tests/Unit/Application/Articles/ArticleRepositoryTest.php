<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles;

use App\Application\Articles\ArticleRepository;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\ArticleMapper;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\ArticleRepository
 */
class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var ArticleRepository */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $queryBuilder = $this->app->make(LaravelQueryBuilder::class);
        $articleMapper = $this->app->make(ArticleMapper::class);
        $this->repository = new ArticleRepository($queryBuilder, $articleMapper);
    }

    /** @test */
    public function itRetrievesOnlyPublishedArticles(): void
    {
        // arrange
        $dateInPast = Carbon::now()->subDay();
        $dateInFuture = Carbon::now()->addDay();
        factory(ArticleEloquentModel::class)->create(['published_at' => $dateInPast, 'status' => ArticleStatus::published(), 'title' => 'article1']);
        factory(ArticleEloquentModel::class)->create(['published_at' => $dateInPast, 'status' => ArticleStatus::unpublished() ,
            'title' => 'article2']);
        factory(ArticleEloquentModel::class)->create(['published_at' => $dateInFuture, 'status' =>
            ArticleStatus::published(), 'title' => 'article3']);
        factory(ArticleEloquentModel::class)->create(['published_at' => $dateInFuture, 'status' => ArticleStatus::unpublished(),
            'title' => 'article4']);

        // act
        /** @var Article[] $articles */
        $articles = $this->repository->allPublishedAndOrdered()->toArray();

        // assert
        $this->assertCount(1, $articles);
        $this->assertEquals('article1', $articles[0]->title());
    }

    /** @test */
    public function itRetrievesPublishedArticlesInTheCorrectOrder(): void
    {
        // arrange
        $yesterday = Carbon::now()->subDay();
        $lastWeek = Carbon::now()->subWeek();
        $lastYear = Carbon::now()->subYear();

        factory(ArticleEloquentModel::class)->create(['published_at' => $lastWeek, 'status' => ArticleStatus::published(), 'title' => 'article2']);
        factory(ArticleEloquentModel::class)->create(['published_at' => $lastYear, 'status' => ArticleStatus::published(), 'title' => 'article3']);
        factory(ArticleEloquentModel::class)->create(['published_at' => $yesterday, 'status' => ArticleStatus::published(), 'title' => 'article1']);

        // act
        /** @var Article[] $articles */
        $articles = $this->repository->allPublishedAndOrdered()->toArray();

        // assert
        $this->assertCount(3, $articles);
        $this->assertEquals('article1', $articles[0]->title());
        $this->assertEquals('article2', $articles[1]->title());
        $this->assertEquals('article3', $articles[2]->title());
    }

    /** @test */
    public function itSavesAnArticle(): void
    {
        // arrange
        $article = new Article(
            '321321',
            'article-content',
            'article-description',
            new DateTimeImmutable(),
            'article-slug',
            ArticleStatus::published(),
            'article-title',
            '123123'
        );

        // act
        $this->repository->insert($article);

        // assert
        $this->assertDatabaseHas('articles', ['title' => 'article-title']);
        // todo: assert event was raised
    }

    /** @test */
    public function itUpdatesAnArticle(): void
    {
        // arrange
        $article = new Article(
            '321321',
            'article-content',
            'article-description',
            new DateTimeImmutable(),
            'article-slug',
            ArticleStatus::published(),
            'old-article-title',
            '123123'
        );

        $this->repository->insert($article);
        $this->assertDatabaseHas('articles', ['title' => 'old-article-title']);

        // act
        $article = new Article(
            '321321',
            'article-content',
            'article-description',
            new DateTimeImmutable(),
            'article-slug',
            ArticleStatus::published(),
            'new-article-title',
            '123123'
        );
        $this->repository->update($article);

        // assert
        $this->assertDatabaseHas('articles', ['title' => 'new-article-title']);
        // todo: assert event was raised
    }
}
