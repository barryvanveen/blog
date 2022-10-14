<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\View\SitemapPresenter;
use App\Application\View\DateTimeFormatterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Pages\PageRepositoryInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use DateTimeImmutable;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\View\SitemapPresenter
 */
class SitemapPresenterTest extends TestCase
{
    private ObjectProphecy|PageRepositoryInterface $pageRepository;

    private ObjectProphecy|ArticleRepositoryInterface $articleRepository;

    private SitemapPresenter $presenter;

    protected function setUp(): void
    {
        $booksPage = $this->getPage([
            'title' => 'Books',
        ]);

        $homePage = $this->getPage([
            'title' => 'Home',
        ]);

        $aboutPage = $this->getPage([
            'title' => 'About',
        ]);

        $this->pageRepository = $this->prophesize(PageRepositoryInterface::class);
        $this->pageRepository->books()->willReturn($booksPage);
        $this->pageRepository->home()->willReturn($homePage);
        $this->pageRepository->about()->willReturn($aboutPage);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturnArgument(0);

        /** @var ObjectProphecy|DateTimeFormatterInterface $dateTimeFormatter */
        $dateTimeFormatter = $this->prophesize(DateTimeFormatterInterface::class);
        $dateTimeFormatter->metadata(Argument::any())->willReturn('metadata-string');

        $this->articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $this->articleRepository->allPublishedAndOrdered()->willReturn(new LaravelCollection());

        $this->presenter = new SitemapPresenter(
            $this->pageRepository->reveal(),
            $urlGenerator->reveal(),
            $dateTimeFormatter->reveal(),
            $this->articleRepository->reveal(),
        );
    }

    /** @test */
    public function itPresentsOnlyPages(): void
    {
        // act
        $result = $this->presenter->present();

        // assert
        $this->assertEquals(3, is_countable($result['items']) ? count($result['items']) : 0);

        $pages = array_column($result['items'], 'url');
        $this->assertContains('books', $pages);
        $this->assertContains('home', $pages);
        $this->assertContains('about', $pages);
    }

    /** @test */
    public function itPresentsPagesAndArticles(): void
    {
        // arrange
        $lastUpdated = new DateTimeImmutable('+1 day');

        $article = $this->getArticle([
            'updated_at' => $lastUpdated,
        ]);

        $this->articleRepository->allPublishedAndOrdered()
            ->willReturn(new LaravelCollection([$article]));

        // act
        $result = $this->presenter->present();

        // assert
        $this->assertEquals(6, is_countable($result['items']) ? count($result['items']) : 0);

        $pages = array_column($result['items'], 'url');
        $this->assertContains('books', $pages);
        $this->assertContains('home', $pages);
        $this->assertContains('about', $pages);
        $this->assertContains('articles.show', $pages);
        $this->assertContains('articles.index', $pages);
        $this->assertContains('articles.rss', $pages);
    }
}
