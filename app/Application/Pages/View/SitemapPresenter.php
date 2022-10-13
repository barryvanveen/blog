<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\SitemapItem;
use App\Domain\Utils\SitemapItemCollection;
use DateTimeInterface;

final class SitemapPresenter implements PresenterInterface
{
    public function __construct(private PageRepositoryInterface $pageRepository, private UrlGeneratorInterface $urlGenerator, private DateTimeFormatterInterface $dateTimeFormatter, private ArticleRepositoryInterface $articleRepository)
    {
    }

    public function present(): array
    {
        $itemCollection = new SitemapItemCollection();

        $articles = $this->getArticles();
        $itemCollection->add(...$articles->items());
        $itemCollection->add(...$this->getPages($articles->lastPublicationDate())->items());

        return [
            'items' => $this->renderSiteMapItems($itemCollection),
        ];
    }

    private function getArticles(): SitemapItemCollection
    {
        $articles = $this->articleRepository->allPublishedAndOrdered();

        return new SitemapItemCollection(
            ...array_map(fn($article) => $this->getArticle($article), $articles->toArray())
        );
    }

    private function getArticle(Article $article): SitemapItem
    {
        return new SitemapItem(
            $this->urlGenerator->route('articles.show', ['uuid' => $article->uuid(), 'slug' => $article->slug()]),
            $article->updatedAt(),
        );
    }

    private function getPages(?DateTimeInterface $lastArticleModificationDate): SitemapItemCollection
    {
        $collection = new SitemapItemCollection(...[
            $this->getPageData('home', $lastArticleModificationDate ?? $this->pageRepository->home()->lastUpdated()),
            $this->getPageData('about', $this->pageRepository->about()->lastUpdated()),
            $this->getPageData('books', $this->pageRepository->books()->lastUpdated()),
        ]);

        if ($lastArticleModificationDate !== null) {
            $collection->add(
                $this->getPageData('articles.index', $lastArticleModificationDate),
                $this->getPageData('articles.rss', $lastArticleModificationDate),
            );
        }

        return $collection;
    }

    private function getPageData(string $route, DateTimeInterface $lastModificationDate): SitemapItem
    {
        return new SitemapItem(
            $this->urlGenerator->route($route),
            $lastModificationDate,
        );
    }

    private function renderSiteMapItems(SitemapItemCollection $collection): array
    {
        return array_map(
            fn($item) => [
                'url' => $item->url(),
                'lastmod' => $this->dateTimeFormatter->metadata($item->lastModificationDate()),
            ],
            $collection->items()
        );
    }
}
