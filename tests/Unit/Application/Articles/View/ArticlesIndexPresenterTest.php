<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\ArticlesIndexPresenter;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Utils\MetaData;
use App\Infrastructure\Adapters\LaravelCollection;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\ArticlesIndexPresenter
 */
class ArticlesIndexPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $collection = new LaravelCollection();

        /** @var ObjectProphecy|ArticleRepositoryInterface $repository */
        $repository = $this->prophesize(ArticleRepositoryInterface::class);
        $repository->allPublishedAndOrdered()->willReturn($collection);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::any())->willReturn('http://myurl');

        $presenter = new ArticlesIndexPresenter(
            $repository->reveal(),
            $urlGenerator->reveal()
        );

        $result = $presenter->present();

        $this->assertArrayHasKey('articles', $result);
        $this->assertEquals($collection, $result['articles']);

        $this->assertArrayHasKey('metaData', $result);
        $this->assertInstanceOf(MetaData::class, $result['metaData']);
    }
}
