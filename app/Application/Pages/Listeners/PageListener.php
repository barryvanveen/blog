<?php

declare(strict_types=1);

namespace App\Application\Pages\Listeners;

use App\Application\Core\BaseEventListener;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\Events\PageWasUpdated;

final class PageListener extends BaseEventListener
{
    /** @var CacheInterface */
    private $cache;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

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
                $route = $this->urlGenerator->route('about');
                break;
            default:
                throw CacheInvalidationException::unkownSlug($slug);
        }

        $this->cache->forget($route);
    }
}
