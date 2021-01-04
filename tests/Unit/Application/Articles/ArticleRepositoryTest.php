<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Events\ArticleWasCreated;
use App\Application\Articles\Events\ArticleWasUpdated;
use App\Application\Exceptions\RecordNotFoundException;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Infrastructure\Adapters\LaravelEventBus;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\ArticleMapper;
use Carbon\Carbon;
use Database\Factories\ArticleFactory;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Testing\Fakes\EventFake;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\ArticleRepository
 */
class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ArticleRepository $repository;

    private EventFake $laravelBusFake;

    private ArticleFactory $articleFactory;

    public function setUp(): void
    {
        parent::setUp();

        $queryBuilder = new LaravelQueryBuilder(new ArticleEloquentModel());
        $articleMapper = $this->app->make(ArticleMapper::class);

        $this->laravelBusFake = Event::fake();
        $eventBus = new LaravelEventBus($this->laravelBusFake);

        $this->repository = new ArticleRepository($queryBuilder, $articleMapper, $eventBus);

        $this->articleFactory = ArticleFactory::new();
    }

    /** @test */
    public function itRetrievesAllArticlesInTheCorrectOrder(): void
    {
        // arrange
        $this->articleFactory->create([
            'published_at' => Carbon::now()->subDays(4),
            'status' => ArticleStatus::published(),
            'title' => 'article1',
        ]);
        $this->articleFactory->create([
            'published_at' => Carbon::now()->subDays(2),
            'status' => ArticleStatus::unpublished(),
            'title' => 'article2',
        ]);
        $this->articleFactory->create([
            'published_at' => Carbon::now()->addDays(2),
            'status' => ArticleStatus::published(),
            'title' => 'article3',
        ]);
        $this->articleFactory->create([
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
        $this->articleFactory->create(['published_at' => $dateInPast, 'status' => ArticleStatus::published(), 'title' => 'article1']);
        $this->articleFactory->create(['published_at' => $dateInPast, 'status' => ArticleStatus::unpublished(), 'title' => 'article2']);
        $this->articleFactory->create(['published_at' => $dateInFuture, 'status' => ArticleStatus::published(), 'title' => 'article3']);
        $this->articleFactory->create(['published_at' => $dateInFuture, 'status' => ArticleStatus::unpublished(), 'title' => 'article4']);

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

        $this->articleFactory->create(['published_at' => $lastWeek, 'status' => ArticleStatus::published(), 'title' => 'article2']);
        $this->articleFactory->create(['published_at' => $lastYear, 'status' => ArticleStatus::published(), 'title' => 'article3']);
        $this->articleFactory->create(['published_at' => $yesterday, 'status' => ArticleStatus::published(), 'title' => 'article1']);

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
        $this->laravelBusFake->assertDispatchedTimes(ArticleWasCreated::class);
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
        $this->laravelBusFake->assertDispatchedTimes(ArticleWasUpdated::class);
    }

    /** @test */
    public function itSetsTheTimestamps(): void
    {
        // arrange
        $article = $this->getArticle();

        // act
        $this->repository->insert($article);

        // assert
        $record = DB::table('articles')->where('uuid', '=', $article->uuid())->first();
        $this->assertNotEmpty($record->created_at);
        $this->assertNotEmpty($record->updated_at);
    }

    /** @test */
    public function itUpdatesTheTimestamps(): void
    {
        // arrange
        $oldDate = new DateTimeImmutable('-1 day');
        $this->articleFactory->create([
            'uuid' => 'myuuid',
            'created_at' => $oldDate,
            'updated_at' => $oldDate,
        ]);

        // act
        $article = $this->getArticle([
            'uuid' => 'myuuid',
        ]);
        $this->repository->update($article);

        // assert
        $record = DB::table('articles')->where('uuid', '=', $article->uuid())->first();
        $this->assertEquals($oldDate->getTimestamp(), (new DateTimeImmutable($record->created_at))->getTimestamp());
        $this->assertNotEmpty($record->updated_at);
        $this->assertTrue($record->updated_at > $record->created_at);
    }

    /** @test */
    public function itRetrievesAPublishedArticleByUUID(): void
    {
        // arrange
        $dateInPast = Carbon::now()->subDay();

        /** @var ArticleEloquentModel $eloquentArticle */
        $eloquentArticle = $this->articleFactory->create([
            'published_at' => $dateInPast,
            'status' => ArticleStatus::published(),
        ]);
        $article = $this->repository->getPublishedByUuid($eloquentArticle->uuid);

        // assert
        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals($eloquentArticle->uuid, $article->uuid());
        $this->assertEquals($eloquentArticle->title, $article->title());
    }

    /** @test */
    public function itThrowsAnExceptionWhenRetrievingAnUnpublishedArticle(): void
    {
        // arrange
        $dateInPast = Carbon::now()->subDay();

        /** @var ArticleEloquentModel $eloquentArticle */
        $eloquentArticle = $this->articleFactory->create([
            'published_at' => $dateInPast,
            'status' => ArticleStatus::unpublished(),
        ]);

        // assert
        $this->expectException(RecordNotFoundException::class);
        $this->repository->getPublishedByUuid($eloquentArticle->uuid);
    }

    /** @test */
    public function itThrowsAnExceptionWhenRetrievingAnArticlePublishedInTheFuture(): void
    {
        // arrange
        $dateInFuture = Carbon::now()->addDay();

        /** @var ArticleEloquentModel $eloquentArticle */
        $eloquentArticle = $this->articleFactory->create([
            'published_at' => $dateInFuture,
            'status' => ArticleStatus::published(),
        ]);

        // assert
        $this->expectException(RecordNotFoundException::class);
        $this->repository->getPublishedByUuid($eloquentArticle->uuid);
    }

    /** @test */
    public function itRetrievesAnArticleByUUID(): void
    {
        // arrange
        /** @var ArticleEloquentModel $eloquentArticle */
        $eloquentArticle = $this->articleFactory->create();
        $article = $this->repository->getByUuid($eloquentArticle->uuid);

        // assert
        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals($eloquentArticle->uuid, $article->uuid());
        $this->assertEquals($eloquentArticle->title, $article->title());
    }
}
