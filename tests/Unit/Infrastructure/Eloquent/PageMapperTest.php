<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Eloquent;

use App\Domain\Core\CollectionInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use Database\Factories\PageFactory;
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
        $eloquentPage = PageFactory::new()->make();

        $mapper = new PageMapper();
        $page = $mapper->mapToDomainModel($eloquentPage->toArray());

        $this->assertEquals($eloquentPage->slug, $page->slug());
        $this->assertEquals($eloquentPage->title, $page->title());
    }

    /** @test */
    public function itConvertsAnArrayOfEloquentModelsIntoCollectionOfDomainModels(): void
    {
        /** @var PageEloquentModel $eloquentPage1 */
        $eloquentPage1 = PageFactory::new()->make();

        /** @var PageEloquentModel $eloquentPage2 */
        $eloquentPage2 = PageFactory::new()->make();

        $eloquentCollection = new LaravelCollection([
            $eloquentPage1->toArray(),
            $eloquentPage2->toArray(),
        ]);

        $mapper = new PageMapper();
        $collection = $mapper->mapToDomainModels($eloquentCollection);

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($eloquentPage1->title, $collection->toArray()[0]->title());
        $this->assertEquals($eloquentPage2->title, $collection->toArray()[1]->title());
    }

    /** @test */
    public function itMapsADomainModelToAnArrayForDatabaseOperations(): void
    {
        $page = $this->getPage();

        $mapper = new PageMapper();
        $record = $mapper->mapToDatabaseArray($page);

        $this->assertEquals($page->title(), $record['title']);
        $this->assertArrayNotHasKey('last_updated', $record);
        $this->assertArrayNotHasKey('lastUpdated', $record);
    }
}
