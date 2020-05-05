<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Eloquent;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Core\CollectionInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\ArticleMapper;
use DateTimeInterface;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Eloquent\ArticleMapper
 */
class ArticleMapperTest extends TestCase
{
    /** @test */
    public function itConvertsAnEloquentModelToDomainModel(): void
    {
        /** @var ArticleEloquentModel $eloquentArticle */
        $eloquentArticle = factory(ArticleEloquentModel::class)->make([
            'published_at' => '2020-05-05 20:36:00',
            'status' => '1',
        ]);

        $mapper = new ArticleMapper();
        $article = $mapper->mapToDomainModel($eloquentArticle);

        $this->assertEquals($eloquentArticle->slug, $article->slug());
        $this->assertEquals($eloquentArticle->title, $article->title());
        $this->assertInstanceOf(DateTimeInterface::class, $article->publishedAt());
        $this->assertTrue(ArticleStatus::published()->equals($article->status()));
    }

    /** @test */
    public function itConvertsAnArrayOfEloquentModelsIntoCollectionOfDomainModels(): void
    {
        /** @var ArticleEloquentModel $eloquentArticle1 */
        $eloquentArticle1 = factory(ArticleEloquentModel::class)->make([
            'published_at' => '2020-05-05 20:36:00',
            'status' => 1,
        ]);

        /** @var ArticleEloquentModel $eloquentArticle2 */
        $eloquentArticle2 = factory(ArticleEloquentModel::class)->make([
            'published_at' => '2020-05-06 20:36:00',
            'status' => 0,
        ]);

        $eloquentCollection = new LaravelCollection([$eloquentArticle1, $eloquentArticle2]);

        $mapper = new ArticleMapper();
        $collection = $mapper->mapToDomainModels($eloquentCollection);

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($eloquentArticle1->title, $collection->toArray()[0]->title());
        $this->assertEquals($eloquentArticle2->title, $collection->toArray()[1]->title());
    }
}
