<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Exceptions\RecordNotFoundException;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Database\Factories\ArticleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelQueryBuilder
 * @covers \App\Application\Exceptions\RecordNotFoundException
 */
class LaravelQueryBuilderTest extends TestCase
{
    use RefreshDatabase;

    /** @var LaravelQueryBuilder */
    private $queryBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = new LaravelQueryBuilder(ArticleEloquentModel::query());
    }

    /** @test */
    public function itGetsAnEmptyCollectionFromAnEmptyDatabase(): void
    {
        $collection = $this->queryBuilder->get();

        $this->assertCount(0, $collection);
    }

    /** @test */
    public function itGetsACollectionOfArticles(): void
    {
        /** @var ArticleEloquentModel[] $articles */
        $articles = ArticleFactory::new()->count(3)->create();

        /** @var ArticleEloquentModel[] $collection */
        $collection = $this->queryBuilder
            ->get()
            ->toArray();

        $this->assertCount(3, $collection);
        $this->assertEquals($articles[0]['uuid'], $collection[0]['uuid']);
        $this->assertEquals($articles[1]['uuid'], $collection[1]['uuid']);
        $this->assertEquals($articles[2]['uuid'], $collection[2]['uuid']);
    }

    /** @test */
    public function itGetsAnAscOrderedCollectionOfArticles(): void
    {
        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'bbb',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'aaa',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'ccc',
        ]);

        /** @var ArticleEloquentModel[] $collection */
        $collection = $this->queryBuilder
            ->orderBy('slug', 'asc')
            ->get()
            ->toArray();

        $this->assertCount(3, $collection);
        $this->assertEquals('aaa', $collection[0]['slug']);
        $this->assertEquals('bbb', $collection[1]['slug']);
        $this->assertEquals('ccc', $collection[2]['slug']);
    }

    /** @test */
    public function itGetsADescOrderedCollectionOfArticles(): void
    {
        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'bbb',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'aaa',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'ccc',
        ]);

        /** @var ArticleEloquentModel[] $collection */
        $collection = $this->queryBuilder
            ->orderBy('slug', 'desc')
            ->get()
            ->toArray();

        $this->assertCount(3, $collection);
        $this->assertEquals('ccc', $collection[0]['slug']);
        $this->assertEquals('bbb', $collection[1]['slug']);
        $this->assertEquals('aaa', $collection[2]['slug']);
    }

    /** @test */
    public function itGetsTheFirstRecord(): void
    {
        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'bbb',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'aaa',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'ccc',
        ]);

        $record = $this->queryBuilder
            ->orderBy('slug', 'desc')
            ->first();

        $this->assertEquals('ccc', $record['slug']);
    }

    /** @test */
    public function itFiltersTheRecordsByEquality(): void
    {
        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'bbb',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'slug' => 'aaa',
        ]);

        $record = $this->queryBuilder
            ->where('slug', '=', 'aaa')
            ->first();

        $this->assertEquals('aaa', $record['slug']);
    }

    /** @test */
    public function itFiltersTheRecordsByComparison(): void
    {
        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'published_at' => '2020-01-01 00:00:00',
            'slug' => 'aaa',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'published_at' => '2020-01-03 00:00:00',
            'slug' => 'bbb',
        ]);

        /** @var ArticleEloquentModel $articles */
        ArticleFactory::new()->create([
            'published_at' => '2020-01-02 00:00:00',
            'slug' => 'ccc',
        ]);

        $collection = $this->queryBuilder
            ->where('published_at', '>=', '2020-01-02 00:00:00')
            ->orderBy('published_at', 'asc')
            ->get()
            ->toArray();

        $this->assertCount(2, $collection);
        $this->assertEquals('ccc', $collection[0]['slug']);
        $this->assertEquals('bbb', $collection[1]['slug']);
    }

    /** @test */
    public function itThrowsAnExceptionIfNoFirstItemCanBeFound(): void
    {
        $this->expectException(RecordNotFoundException::class);

        $this->queryBuilder
            ->where('slug', '=', 'xxx')
            ->first();
    }

    /** @test */
    public function itInsertsARecord(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = ArticleFactory::new()->make();

        $this->assertDatabaseMissing('articles', [
            'uuid' => $article->uuid,
        ]);

        $this->queryBuilder
            ->insert([
                 'content' => $article->content,
                 'description' => $article->description,
                 'published_at' => $article->published_at,
                 'slug' => $article->slug,
                 'status' => $article->status,
                 'title' => $article->title,
                 'uuid' => $article->uuid,
            ]);

        $this->assertDatabaseHas('articles', [
            'uuid' => $article->uuid,
        ]);
    }

    /** @test */
    public function itUpdatesARecord(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = ArticleFactory::new()->create();

        $this->assertDatabaseHas('articles', [
            'uuid' => $article->uuid,
        ]);

        $this->queryBuilder
            ->where('uuid', '=', $article->uuid)
            ->update([
                'uuid' => 'newUuid',
            ]);

        $this->assertDatabaseMissing('articles', [
            'uuid' => $article->uuid,
        ]);
        $this->assertDatabaseHas('articles', [
            'uuid' => 'newUuid',
        ]);
    }
}
