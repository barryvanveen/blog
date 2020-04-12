<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles;

use App\Application\Articles\ArticleRepository;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Infrastructure\Adapters\LaravelQueryBuilderFactory;
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

        $queryBuilder = $this->app->make(LaravelQueryBuilderFactory::class);
        $articleMapper = $this->app->make(ArticleMapper::class);
        $this->repository = new ArticleRepository($queryBuilder, $articleMapper);
    }

    /** @test */
    public function itRetrievesAllArticlesInTheCorrectOrder(): void
    {
        // arrange
        factory(ArticleEloquentModel::class)->create([
            'published_at' => Carbon::now()->subDays(4),
            'status' => ArticleStatus::published(),
            'title' => 'article1',
        ]);
        factory(ArticleEloquentModel::class)->create([
            'published_at' => Carbon::now()->subDays(2),
            'status' => ArticleStatus::unpublished(),
            'title' => 'article2',
        ]);
        factory(ArticleEloquentModel::class)->create([
            'published_at' => Carbon::now()->addDays(2),
            'status' => ArticleStatus::published(),
            'title' => 'article3',
        ]);
        factory(ArticleEloquentModel::class)->create([
            'published_at' => Carbon::now()->addDays(4),
            'status' => ArticleStatus::unpublished(),
            'title' => 'article4',
        ]);

        // act
        /** @var Article[] $articles */
        $articles = $this->repository->allOrdered()->toArray();

        // assert
        $this->assertCount(4, $articles);
        $this->assertEquals('article4', $articles[0]->title());
        $this->assertEquals('article3', $articles[1]->title());
        $this->assertEquals('article2', $articles[2]->title());
        $this->assertEquals('article1', $articles[3]->title());
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
    }

    /** @test */
    public function itUpdatesAnArticle(): void
    {
        // arrange
        $article = new Article(
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
    }

    /** @test */
    public function itRetrievesAnArticleByUUID(): void
    {
        // arrange
        /** @var ArticleEloquentModel $eloquentArticle */
        $eloquentArticle = factory(ArticleEloquentModel::class)->create();
        $article = $this->repository->getByUuid($eloquentArticle->uuid);

        // assert
        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals($eloquentArticle->uuid, $article->uuid());
        $this->assertEquals($eloquentArticle->title, $article->title());
    }
}
