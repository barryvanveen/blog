<?php

declare(strict_types=1);

namespace App\Application\Pages\Listeners;

use App\Application\Core\BaseEventListener;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\Events\PageWasUpdated;

final class PageListener extends BaseEventListener
{
    public function __construct(
        private CacheInterface $cache,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function handlePageWasUpdated(PageWasUpdated $event): void
    {
        $slug = $event->slug();

        switch ($slug) {
            case 'about':
                $this->clearRouteCache('about');
                $this->clearRouteCache('sitemap');
                break;
            case 'books':
                $this->clearRouteCache('books');
                $this->clearRouteCache('home');
                $this->clearRouteCache('sitemap');
                break;
            case 'home':
                $this->clearRouteCache('home');
                $this->clearRouteCache('sitemap');
                break;
            case 'music':
                $this->clearRouteCache('music');
                $this->clearRouteCache('sitemap');
                break;
            default:
                throw CacheInvalidationException::unkownSlug($slug);
        }
    }

    private function clearRouteCache(string $route): void
    {
        $url = $this->urlGenerator->route($route);
        $this->cache->forget($url);
    }
}
