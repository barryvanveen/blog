<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\View;

use App\Application\Interfaces\ClockInterface;
use App\Application\Interfaces\LastfmInterface;
use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\View\PagesMusicPresenter;
use App\Application\View\DateTimeFormatterInterface;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;
use DateTimeImmutable;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\View\PagesMusicPresenter
 */
class PagesMusicPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $title = 'titleString';

        $albums = ['foo'=>'bar'];

        $page = $this->getPage([
            'title' => $title,
            'content' => 'contentString',
        ]);

        /** @var ObjectProphecy|PageRepositoryInterface $repository */
        $repository = $this->prophesize(PageRepositoryInterface::class);
        $repository->music()->willReturn($page);

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

        /** @var ObjectProphecy|LastfmInterface $lastfm */
        $lastfm = $this->prophesize(LastfmInterface::class);
        $lastfm->topAlbumsForLastMonth()->willReturn($albums);

        /** @var ObjectProphecy|ClockInterface $clock */
        $clock = $this->prophesize(ClockInterface::class);
        $clock->now()->willReturn(new DateTimeImmutable());

        $presenter = new PagesMusicPresenter(
            $repository->reveal(),
            $urlGenerator->reveal(),
            $dateTimeFormatter->reveal(),
            $markdownConverter->reveal(),
            $lastfm->reveal(),
            $clock->reveal(),
        );

        $result = $presenter->present();

        $this->assertEquals($title, $result['title']);
        $this->assertEquals('metadata-string', $result['lastUpdatedDateInAtomFormat']);
        $this->assertEquals('humanReadable-string', $result['lastUpdatedDateInHumanFormat']);
        $this->assertEquals('htmlString', $result['content']);
        $this->assertEquals($albums, $result['albums']);
        $this->assertInstanceOf(MetaData::class, $result['metaData']);
    }
}
