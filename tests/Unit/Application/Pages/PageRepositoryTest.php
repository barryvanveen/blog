<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages;

use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Pages\Events\PageWasUpdated;
use App\Application\Pages\PageRepository;
use App\Domain\Pages\Models\Page;
use App\Infrastructure\Adapters\LaravelEventBus;
use App\Infrastructure\Adapters\LaravelQueryBuilderFactory;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Testing\Fakes\EventFake;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\PageRepository
 */
class PageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var PageRepository */
    protected $repository;

    /** @var EventFake */
    private $laravelBusFake;

    public function setUp(): void
    {
        parent::setUp();

        $queryBuilder = $this->app->make(LaravelQueryBuilderFactory::class);
        $pageMapper = $this->app->make(PageMapper::class);

        $this->laravelBusFake = Event::fake();
        $eventBus = new LaravelEventBus($this->laravelBusFake);

        $this->repository = new PageRepository($queryBuilder, $pageMapper, $eventBus);
    }

    /** @test */
    public function itRetrievesAllArticlesInTheCorrectOrder(): void
    {
        // arrange
        factory(PageEloquentModel::class)->create(['slug' => 'page3']);
        factory(PageEloquentModel::class)->create(['slug' => 'page2']);
        factory(PageEloquentModel::class)->create(['slug' => 'page1']);
        factory(PageEloquentModel::class)->create(['slug' => 'page4']);

        // act
        /** @var Page[] $pages */
        $pages = $this->repository->allOrdered()->toArray();

        // assert
        $this->assertCount(4, $pages);
        $this->assertEquals('page1', $pages[0]->slug());
        $this->assertEquals('page2', $pages[1]->slug());
        $this->assertEquals('page3', $pages[2]->slug());
        $this->assertEquals('page4', $pages[3]->slug());
    }

    /** @test */
    public function itSavesAPage(): void
    {
        // arrange
        $page = new Page(
            'page-content',
            'page-description',
            'page-slug',
            'page-title'
        );

        // act
        $this->repository->insert($page);

        // assert
        $this->assertDatabaseHas('pages', ['slug' => 'page-slug']);
    }

    /** @test */
    public function itUpdatesAPage(): void
    {
        // arrange
        $page = new Page(
            'page-content',
            'page-description',
            'page-slug',
            'old-page-title'
        );

        $this->repository->insert($page);
        $this->assertDatabaseHas('pages', ['title' => 'old-page-title']);

        // act
        $page = new Page(
            'page-content',
            'page-description',
            'page-slug',
            'new-page-title'
        );
        $this->repository->update($page);

        // assert
        $this->assertDatabaseHas('pages', ['title' => 'new-page-title']);
        $this->laravelBusFake->assertDispatchedTimes(PageWasUpdated::class);
    }

    /** @test */
    public function itRetrievesAPageBySlug(): void
    {
        // arrange
        /** @var PageEloquentModel $eloquentPage */
        $eloquentPage = factory(PageEloquentModel::class)->create();
        $page = $this->repository->getBySlug($eloquentPage->slug);

        // assert
        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals($eloquentPage->slug, $page->slug());
        $this->assertEquals($eloquentPage->title, $page->title());
    }

    /** @test */
    public function itThrowsAnExceptionWhenRetrievingAnUnknownPage(): void
    {
        // assert
        $this->expectException(RecordNotFoundException::class);
        $this->repository->getBySlug('non-existent-slug');
    }
}
