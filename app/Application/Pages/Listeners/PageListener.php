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
                $this->cache->forget($this->urlGenerator->route('about'));
                break;
            case 'books':
                $this->cache->forget($this->urlGenerator->route('books'));
                $this->cache->forget($this->urlGenerator->route('home'));
                break;
            case 'home':
                $this->cache->forget($this->urlGenerator->route('home'));
                break;
            default:
                throw CacheInvalidationException::unkownSlug($slug);
        }
    }
}
