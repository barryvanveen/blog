<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\ArticlesIndexPresenter;
use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Utils\MetaData;
use App\Infrastructure\Adapters\LaravelCollection;
use DateTimeImmutable;
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
        $article1 = new Article(
            'myContent',
            'myDescription',
            new DateTimeImmutable(),
            'my-slug',
            ArticleStatus::published(),
            'myTitle',
            'my-uuid'
        );

        $collection = new LaravelCollection([
            $article1,
        ]);

        /** @var ObjectProphecy|ArticleRepositoryInterface $repository */
        $repository = $this->prophesize(ArticleRepositoryInterface::class);
        $repository->allPublishedAndOrdered()->willReturn($collection);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://myurl');

        /** @var ObjectProphecy|MarkdownConverterInterface $markdownConverter */
        $markdownConverter = $this->prophesize(MarkdownConverterInterface::class);
        $markdownConverter->convertToHtml(Argument::any())->willReturn('htmlString');

        /** @var ObjectProphecy|DateTimeFormatterInterface $dateTimeFormatter */
        $dateTimeFormatter = $this->prophesize(DateTimeFormatterInterface::class);
        $dateTimeFormatter->humanReadable(Argument::any())->willReturn('humanReadableDate');
        $dateTimeFormatter->metadata(Argument::any())->willReturn('metaDate');

        /** @var ObjectProphecy|CommentRepositoryInterface $commentRepository */
        $commentRepository = $this->prophesize(CommentRepositoryInterface::class);
        $commentRepository->onlineOrderedByArticleUuid(Argument::any())->willReturn(
            new LaravelCollection([
                $this->getComment(),
                $this->getComment(),
            ])
        );

        $presenter = new ArticlesIndexPresenter(
            $repository->reveal(),
            $urlGenerator->reveal(),
            $markdownConverter->reveal(),
            $dateTimeFormatter->reveal(),
            $commentRepository->reveal()
        );

        $result = $presenter->present();

        $this->assertArrayHasKey('articles', $result);
        $this->assertCount(1, $result['articles']);
        $this->assertEquals('myTitle', $result['articles'][0]['title']);
        $this->assertEquals('htmlString', $result['articles'][0]['description']);
        $this->assertEquals('humanReadableDate', $result['articles'][0]['publication_date']);
        $this->assertEquals('metaDate', $result['articles'][0]['publication_date_meta']);
        $this->assertEquals(2, $result['articles'][0]['comments']);

        $this->assertArrayHasKey('metaData', $result);
        $this->assertInstanceOf(MetaData::class, $result['metaData']);
    }
}
