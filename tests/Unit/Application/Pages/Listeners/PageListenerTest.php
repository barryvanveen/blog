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

        $this->listener = new PageListener(
            $this->cache->reveal(),
            $this->urlGenerator->reveal()
        );
    }

    /** @test */
    public function itClearsCachesWhenAnPageWasUpdated(): void
    {
        $this->cache->forget('aboutUrl')
            ->shouldBeCalled();

        $this->listener->handle(new PageWasUpdated('about'));
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
