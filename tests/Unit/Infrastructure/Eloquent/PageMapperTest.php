<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Eloquent;

use App\Domain\Core\CollectionInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Eloquent\PageMapper
 */
class PageMapperTest extends TestCase
{
    /** @test */
    public function itConvertsAnEloquentModelToDomainModel(): void
    {
        /** @var PageEloquentModel $eloquentPage */
        $eloquentPage = factory(PageEloquentModel::class)->make();

        $mapper = new PageMapper();
        $page = $mapper->mapToDomainModel($eloquentPage);

        $this->assertEquals($eloquentPage->slug, $page->slug());
        $this->assertEquals($eloquentPage->title, $page->title());
    }

    /** @test */
    public function itConvertsAnArrayOfEloquentModelsIntoCollectionOfDomainModels(): void
    {
        /** @var PageEloquentModel $eloquentPage1 */
        $eloquentPage1 = factory(PageEloquentModel::class)->make();

        /** @var PageEloquentModel $eloquentPage2 */
        $eloquentPage2 = factory(PageEloquentModel::class)->make();

        $eloquentCollection = new LaravelCollection([$eloquentPage1, $eloquentPage2]);

        $mapper = new PageMapper();
        $collection = $mapper->mapToDomainModels($eloquentCollection);

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($eloquentPage1->title, $collection->toArray()[0]->title());
        $this->assertEquals($eloquentPage2->title, $collection->toArray()[1]->title());
    }
}
