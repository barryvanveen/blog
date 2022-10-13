<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\View;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\View\PagesHomePresenter;
use App\Application\View\DateTimeFormatterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;
use App\Infrastructure\Adapters\LaravelCollection;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\View\PagesHomePresenter
 */
class PagesHomePresenterTest extends TestCase
{
    private const TITLE_STRING = 'titleString';
    private const CONTENT_STRING = 'contentString';

    private ObjectProphecy|PageRepositoryInterface $pageRepository;

    private ObjectProphecy|MarkdownConverterInterface $markdownConverter;

    private ObjectProphecy|ArticleRepositoryInterface $articleRepository;

    private ObjectProphecy|LoggerInterface $logger;

    private PagesHomePresenter $presenter;

    protected function setUp(): void
    {
        $booksPage = $this->getPage([
            'title' => self::TITLE_STRING,
            'content' => self::CONTENT_STRING,
        ]);

        $homePage = $this->getPage([
            'title' => self::TITLE_STRING,
            'content' => self::CONTENT_STRING,
        ]);

        $this->pageRepository = $this->prophesize(PageRepositoryInterface::class);
        $this->pageRepository->books()->willReturn($booksPage);
        $this->pageRepository->home()->willReturn($homePage);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://my-page-url');

        /** @var ObjectProphecy|DateTimeFormatterInterface $dateTimeFormatter */
        $dateTimeFormatter = $this->prophesize(DateTimeFormatterInterface::class);
        $dateTimeFormatter->metadata(Argument::any())->willReturn('metadata-string');
        $dateTimeFormatter->humanReadable(Argument::any())->willReturn('humanReadable-string');

        $this->markdownConverter = $this->prophesize(MarkdownConverterInterface::class);
        $this->markdownConverter->convertToHtml(Argument::any())->willReturn('htmlString');

        $this->articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $this->articleRepository->allPublishedAndOrdered()->willReturn(new LaravelCollection());

        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->presenter = new PagesHomePresenter(
            $this->pageRepository->reveal(),
            $urlGenerator->reveal(),
            $dateTimeFormatter->reveal(),
            $this->markdownConverter->reveal(),
            $this->articleRepository->reveal(),
            $this->logger->reveal()
        );
    }

    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $this->logger->error(Argument::cetera())->shouldNotBeCalled();

        $result = $this->presenter->present();

        $this->assertEquals(self::TITLE_STRING, $result['title']);
        $this->assertEquals('metadata-string', $result['lastUpdatedDateInAtomFormat']);
        $this->assertEquals('humanReadable-string', $result['lastUpdatedDateInHumanFormat']);
        $this->assertEquals('htmlString', $result['content']);
        $this->assertInstanceOf(MetaData::class, $result['metaData']);
    }

    /** @test */
    public function itPresentsMostRecentArticles(): void
    {
        $homePage = $this->getPage([
            'content' => '{{ARTICLES}}',
        ]);
        $this->pageRepository->home()->willReturn($homePage);

        $articles = new LaravelCollection([
            $this->getArticle(['title' => 'Article1']),
            $this->getArticle(['title' => 'Article2']),
            $this->getArticle(['title' => 'Article3']),
            $this->getArticle(['title' => 'Article4']),
            $this->getArticle(['title' => 'Article5']),
        ]);
        $this->articleRepository->allPublishedAndOrdered()->willReturn($articles);

        $this->presenter->present();

        $this->markdownConverter->convertToHtml('- [Article1](http://my-page-url)
- [Article2](http://my-page-url)
- [Article3](http://my-page-url)
')
            ->shouldBeCalled()
            ->willReturn('htmlString');
    }

    /** @test */
    public function itPresentsLessThenThreeArticles(): void
    {
        $homePage = $this->getPage([
            'content' => '{{ARTICLES}}',
        ]);
        $this->pageRepository->home()->willReturn($homePage);

        $articles = new LaravelCollection([
            $this->getArticle(['title' => 'Article1']),
            $this->getArticle(['title' => 'Article2']),
        ]);
        $this->articleRepository->allPublishedAndOrdered()->willReturn($articles);

        $this->presenter->present();

        $this->markdownConverter->convertToHtml('- [Article1](http://my-page-url)
- [Article2](http://my-page-url)
')
            ->shouldBeCalled()
            ->willReturn('htmlString');
    }

    /** @test */
    public function itPresentsNoArticles(): void
    {
        $homePage = $this->getPage([
            'content' => '{{ARTICLES}}',
        ]);
        $this->pageRepository->home()->willReturn($homePage);

        $articles = new LaravelCollection();
        $this->articleRepository->allPublishedAndOrdered()->willReturn($articles);

        $this->presenter->present();

        $this->markdownConverter->convertToHtml('That\'s strange, no articles yet...
')
            ->shouldBeCalled()
            ->willReturn('htmlString');

        $this->logger->error('Could not find articles for home page')
            ->shouldBeCalled();
    }

    /** @test */
    public function itPresentsNoBooksWhenNotCurrentlyReadingAnything(): void
    {
        $homePage = $this->getPage([
            'content' => '{{BOOKS}}',
        ]);
        $this->pageRepository->home()->willReturn($homePage);

        $booksPage = $this->getPage([
            'content' => '',
        ]);
        $this->pageRepository->books()->willReturn($booksPage);

        $this->presenter->present();

        $this->markdownConverter->convertToHtml('Not reading anything, actually
')
            ->shouldBeCalled()
            ->willReturn('htmlString');

        $this->logger->error('Could not parse books text (start) for homepage')
            ->shouldBeCalled();
    }

    /** @test */
    public function itPresentsNoBooksWhenItCannotDetermineEndOfCurrentlyReading(): void
    {
        $homePage = $this->getPage([
            'content' => '{{BOOKS}}',
        ]);
        $this->pageRepository->home()->willReturn($homePage);

        $booksPage = $this->getPage([
            'content' => '## Currently reading:
- Book1
- Book2
',
        ]);
        $this->pageRepository->books()->willReturn($booksPage);

        $this->presenter->present();

        $this->markdownConverter->convertToHtml('Not reading anything, actually
')
            ->shouldBeCalled()
            ->willReturn('htmlString');

        $this->logger->error('Could not parse books text (end) for homepage')
            ->shouldBeCalled();
    }

    /** @test */
    public function itPresentsBooks(): void
    {
        $homePage = $this->getPage([
            'content' => '{{BOOKS}}',
        ]);
        $this->pageRepository->home()->willReturn($homePage);

        $booksPage = $this->getPage([
            'content' => '## Currently reading:
- Book1
- Book2

## Next heading
',
        ]);
        $this->pageRepository->books()->willReturn($booksPage);

        $this->presenter->present();

        $this->markdownConverter->convertToHtml('
- Book1
- Book2

')
            ->shouldBeCalled()
            ->willReturn('htmlString');
    }
}
