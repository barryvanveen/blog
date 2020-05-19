<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\View\AdminPagesIndexPresenter;
use App\Domain\Pages\PageRepositoryInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\View\AdminPagesIndexPresenter
 */
class AdminPagesIndexPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var PageEloquentModel $page1 */
        $page1 = factory(PageEloquentModel::class)->make([
            'slug' => 'page1',
        ]);

        /** @var PageEloquentModel $page2 */
        $page2 = factory(PageEloquentModel::class)->make([
            'slug' => 'page2',
        ]);

        $mapper = new PageMapper();

        $collection = $mapper->mapToDomainModels(
            new LaravelCollection([
                $page1->toArray(),
                $page2->toArray(),
            ])
        );

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::exact('admin.pages.index'))->willReturn('http://indexurl');
        $urlGenerator->route(Argument::exact('admin.pages.create'))->willReturn('http://newurl');
        $urlGenerator->route(Argument::exact('admin.pages.edit'), Argument::type('array'))->willReturn('http://editurl');

        /** @var ObjectProphecy|PageRepositoryInterface $repository */
        $repository = $this->prophesize(PageRepositoryInterface::class);
        $repository->allOrdered()->willReturn($collection);

        $presenter = new AdminPagesIndexPresenter(
            $repository->reveal(),
            $urlGenerator->reveal()
        );

        $result = $presenter->present();

        $this->assertArrayHasKey('pages', $result);
        $this->assertEquals([
            [
                'slug' => $page1->slug,
                'title' => $page1->title,
                'edit_url' => 'http://editurl',
            ],
            [
                'slug' => $page2->slug,
                'title' => $page2->title,
                'edit_url' => 'http://editurl',
            ],
        ], $result['pages']);
    }
}
