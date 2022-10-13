<?php

declare(strict_types=1);

namespace App\Application\Articles\Listeners;

use App\Application\Articles\Events\ArticleWasUpdated;
use App\Application\Core\BaseEventListener;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;

final class ArticleListener extends BaseEventListener
{
    public function __construct(private CacheInterface $cache, private UrlGeneratorInterface $urlGenerator, private ArticleRepositoryInterface $articleRepository)
    {
    }

    public function handleArticleWasCreated(): void
    {
        $this->clearArticleIndexCache();

        $this->clearArticleRssCache();

        $this->clearHomePageCache();

        $this->clearSitemapCache();
    }

    public function handleArticleWasUpdated(ArticleWasUpdated $event): void
    {
        $article = $this->articleRepository->getByUuid($event->uuid());

        $articleUrl = $this->urlGenerator->route('articles.show', [
            'uuid' => $article->uuid(),
            'slug' => $article->slug(),
        ]);
        $this->cache->forget($articleUrl);

        $this->clearArticleIndexCache();

        $this->clearArticleRssCache();

        $this->clearHomePageCache();

        $this->clearSitemapCache();
    }

    private function clearArticleIndexCache(): void
    {
        $indexUrl = $this->urlGenerator->route('articles.index');
        $this->cache->forget($indexUrl);
    }

    private function clearArticleRssCache(): void
    {
        $rssUrl = $this->urlGenerator->route('articles.rss');
        $this->cache->forget($rssUrl);
    }

    private function clearHomePageCache(): void
    {
        $homeUrl = $this->urlGenerator->route('home');
        $this->cache->forget($homeUrl);
    }

    private function clearSitemapCache(): void
    {
        $sitemapUrl = $this->urlGenerator->route('sitemap');
        $this->cache->forget($sitemapUrl);
    }
}
