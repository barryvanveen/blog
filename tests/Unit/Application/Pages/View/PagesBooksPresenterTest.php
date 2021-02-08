<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\View;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\View\PagesAboutPresenter;
use App\Application\View\DateTimeFormatterInterface;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\View\PagesBooksPresenter
 */
class PagesBooksPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $title = 'titleString';

        $page = $this->getPage([
            'title' => $title,
            'content' => 'contentString',
        ]);

        /** @var ObjectProphecy|PageRepositoryInterface $repository */
        $repository = $this->prophesize(PageRepositoryInterface::class);
        $repository->about()->willReturn($page);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://my-page-url');

        /** @var ObjectProphecy|DateTimeFormatterInterface $dateTimeFormatter */
        $dateTimeFormatter = $this->prophesize(DateTimeFormatterInterface::class);
        $dateTimeFormatter->metadata(Argument::any())->willReturn('metadata-string');
        $dateTimeFormatter->humanReadable(Argument::any())->willReturn('humanReadable-string');

        /** @var ObjectProphecy|MarkdownConverterInterface $markdownConverter */
        $markdownConverter = $this->prophesize(MarkdownConverterInterface::class);
        $markdownConverter->convertToHtml(Argument::any())->willReturn('htmlString');

        $presenter = new PagesAboutPresenter(
            $repository->reveal(),
            $urlGenerator->reveal(),
            $dateTimeFormatter->reveal(),
            $markdownConverter->reveal()
        );

        $result = $presenter->present();

        $this->assertEquals($title, $result['title']);
        $this->assertEquals('metadata-string', $result['lastUpdatedDateInAtomFormat']);
        $this->assertEquals('humanReadable-string', $result['lastUpdatedDateInHumanFormat']);
        $this->assertEquals('htmlString', $result['content']);
        $this->assertInstanceOf(MetaData::class, $result['metaData']);
    }
}
