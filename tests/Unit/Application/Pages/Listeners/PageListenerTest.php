<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\Listeners;

use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\Events\PageWasUpdated;
use App\Application\Pages\Listeners\CacheInvalidationException;
use App\Application\Pages\Listeners\PageListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\Events\PageWasUpdated
 * @covers \App\Application\Pages\Listeners\PageListener
 * @covers \App\Application\Pages\Listeners\CacheInvalidationException
 */
class PageListenerTest extends TestCase
{
    /** @var ObjectProphecy|CacheInterface */
    private $cache;

    /** @var ObjectProphecy|UrlGeneratorInterface */
    private $urlGenerator;

    /** @var PageListener() */
    private $listener;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->prophesize(CacheInterface::class);

        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->urlGenerator->route('about')->willReturn('aboutUrl');
        $this->urlGenerator->route('books')->willReturn('booksUrl');
        $this->urlGenerator->route('home')->willReturn('homeUrl');
        $this->urlGenerator->route('sitemap')->willReturn('sitemapUrl');

        $this->listener = new PageListener(
            $this->cache->reveal(),
            $this->urlGenerator->reveal()
        );
    }

    /** @test */
    public function itClearsCachesWhenAboutPageWasUpdated(): void
    {
        $this->cache->forget('aboutUrl')
            ->shouldBeCalled();

        $this->cache->forget('sitemapUrl')
            ->shouldBeCalled();

        $this->listener->handle(new PageWasUpdated('about'));
    }

    /** @test */
    public function itClearsCachesWhenBooksPageWasUpdated(): void
    {
        $this->cache->forget('booksUrl')
            ->shouldBeCalled();

        $this->cache->forget('homeUrl')
            ->shouldBeCalled();

        $this->cache->forget('sitemapUrl')
            ->shouldBeCalled();

        $this->listener->handle(new PageWasUpdated('books'));
    }

    /** @test */
    public function itClearsCachesWhenHomePageWasUpdated(): void
    {
        $this->cache->forget('homeUrl')
            ->shouldBeCalled();

        $this->cache->forget('sitemapUrl')
            ->shouldBeCalled();

        $this->listener->handle(new PageWasUpdated('home'));
    }

    /** @test */
    public function itThrowsAnExceptionWhenSlugCannotBeMatchedToRoute(): void
    {
        $this->cache->forget(Argument::any())
            ->shouldNotBeCalled();

        $this->expectException(CacheInvalidationException::class);

        $this->listener->handle(new PageWasUpdated('foo'));
    }
}
