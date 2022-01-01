<?php

declare(strict_types=1);

namespace App\Application\Pages\Listeners;

use App\Application\Core\BaseEventListener;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\Events\PageWasUpdated;

final class PageListener extends BaseEventListener
{
    private CacheInterface $cache;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        CacheInterface $cache,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->cache = $cache;
        $this->urlGenerator = $urlGenerator;
    }

    public function handlePageWasUpdated(PageWasUpdated $event): void
    {
        $slug = $event->slug();

        switch ($slug) {
            case 'about':
                $this->clearAboutPageCache();
                $this->clearSitemapCache();
                break;
            case 'books':
                $this->clearBooksPageCache();
                $this->clearHomePageCache();
                $this->clearSitemapCache();
                break;
            case 'home':
                $this->clearHomePageCache();
                $this->clearSitemapCache();
                break;
            default:
                throw CacheInvalidationException::unkownSlug($slug);
        }
    }

    private function clearAboutPageCache(): void
    {
        $aboutUrl = $this->urlGenerator->route('about');
        $this->cache->forget($aboutUrl);
    }

    private function clearBooksPageCache(): void
    {
        $booksUrl = $this->urlGenerator->route('books');
        $this->cache->forget($booksUrl);
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
