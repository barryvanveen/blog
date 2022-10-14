<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages;

use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Pages\Events\PageWasUpdated;
use App\Application\Pages\PageRepository;
use App\Domain\Pages\Models\Page;
use App\Infrastructure\Adapters\LaravelEventBus;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use Database\Factories\PageFactory;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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

    private EventFake $laravelBusFake;

    public function setUp(): void
    {
        parent::setUp();

        $queryBuilder = new LaravelQueryBuilder(new PageEloquentModel());
        $pageMapper = $this->app->make(PageMapper::class);

        $this->laravelBusFake = Event::fake();
        $eventBus = new LaravelEventBus($this->laravelBusFake);

        $this->repository = new PageRepository($queryBuilder, $pageMapper, $eventBus);
    }

    /** @test */
    public function itRetrievesAllPagesInTheCorrectOrder(): void
    {
        // arrange
        PageFactory::new()->create(['slug' => 'page3']);
        PageFactory::new()->create(['slug' => 'page2']);
        PageFactory::new()->create(['slug' => 'page1']);
        PageFactory::new()->create(['slug' => 'page4']);

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
        $page = $this->getPage();

        // act
        $this->repository->insert($page);

        // assert
        $this->assertDatabaseHas('pages', ['slug' => $page->slug()]);
    }

    /** @test */
    public function itUpdatesAPage(): void
    {
        // arrange
        $page = $this->getPage([
            'slug' => 'myslug',
            'title' => 'old-page-title',
        ]);

        $this->repository->insert($page);
        $this->assertDatabaseHas('pages', ['title' => $page->title()]);

        // act
        $page = $this->getPage([
            'slug' => 'myslug',
            'title' => 'old-page-title',
        ]);

        $this->repository->update($page);

        // assert
        $this->assertDatabaseHas('pages', ['title' => $page->title()]);
        $this->laravelBusFake->assertDispatchedTimes(PageWasUpdated::class);
    }

    /** @test */
    public function itSetsTheTimestamps(): void
    {
        // arrange
        $page = $this->getPage();

        // act
        $this->repository->insert($page);

        // assert
        $record = DB::table('pages')->where('slug', '=', $page->slug())->first();
        $this->assertNotEmpty($record->created_at);
        $this->assertNotEmpty($record->updated_at);
    }

    /** @test */
    public function itUpdatesTheTimestamps(): void
    {
        // arrange
        $oldDate = new DateTimeImmutable('-1 day');
        PageFactory::new()->create([
            'slug' => 'myslug',
            'created_at' => $oldDate,
            'updated_at' => $oldDate,
        ]);

        // act
        $page = $this->getPage([
            'slug' => 'myslug',
        ]);
        $this->repository->update($page);

        // assert
        $record = DB::table('pages')->where('slug', '=', $page->slug())->first();
        $this->assertEquals($oldDate->getTimestamp(), (new DateTimeImmutable($record->created_at))->getTimestamp());
        $this->assertNotEmpty($record->updated_at);
        $this->assertTrue($record->updated_at > $record->created_at);
    }

    /** @test */
    public function itRetrievesAboutPageBySlug(): void
    {
        // arrange
        /** @var PageEloquentModel $eloquentPage */
        $eloquentPage = PageFactory::new()->create([
            'slug' => 'about',
        ]);
        $page = $this->repository->about();

        // assert
        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals($eloquentPage->slug, $page->slug());
        $this->assertEquals($eloquentPage->title, $page->title());
    }

    /** @test */
    public function itThrowsAnExceptionWhenRetrievingUnknownAboutPage(): void
    {
        // assert
        $this->expectException(RecordNotFoundException::class);
        $this->repository->about();
    }

    /** @test */
    public function itRetrievesBooksPageBySlug(): void
    {
        // arrange
        /** @var PageEloquentModel $eloquentPage */
        $eloquentPage = PageFactory::new()->create([
            'slug' => 'books',
        ]);
        $page = $this->repository->books();

        // assert
        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals($eloquentPage->slug, $page->slug());
        $this->assertEquals($eloquentPage->title, $page->title());
    }

    /** @test */
    public function itThrowsAnExceptionWhenRetrievingUnknownBooksPage(): void
    {
        // assert
        $this->expectException(RecordNotFoundException::class);
        $this->repository->books();
    }

    /** @test */
    public function itRetrievesHomePageBySlug(): void
    {
        // arrange
        /** @var PageEloquentModel $eloquentPage */
        $eloquentPage = PageFactory::new()->create([
            'slug' => 'home',
        ]);
        $page = $this->repository->home();

        // assert
        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals($eloquentPage->slug, $page->slug());
        $this->assertEquals($eloquentPage->title, $page->title());
    }

    /** @test */
    public function itThrowsAnExceptionWhenRetrievingUnknownHomePage(): void
    {
        // assert
        $this->expectException(RecordNotFoundException::class);
        $this->repository->home();
    }

    /** @test */
    public function itRetrievesMusicPageBySlug(): void
    {
        // arrange
        /** @var PageEloquentModel $eloquentPage */
        $eloquentPage = PageFactory::new()->create([
            'slug' => 'music',
        ]);
        $page = $this->repository->music();

        // assert
        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals($eloquentPage->slug, $page->slug());
        $this->assertEquals($eloquentPage->title, $page->title());
    }

    /** @test */
    public function itThrowsAnExceptionWhenRetrievingUnknownMusicPage(): void
    {
        // assert
        $this->expectException(RecordNotFoundException::class);
        $this->repository->music();
    }
}
