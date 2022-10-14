<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\Listeners;

use App\Application\Articles\Events\ArticleWasCreated;
use App\Application\Articles\Events\ArticleWasUpdated;
use App\Application\Articles\Listeners\ArticleListener;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\Events\ArticleWasCreated
 * @covers \App\Application\Articles\Events\ArticleWasUpdated
 * @covers \App\Application\Articles\Listeners\ArticleListener
 */
class ArticleListenerTest extends TestCase
{
    private ObjectProphecy|CacheInterface $cache;

    private ObjectProphecy|UrlGeneratorInterface $urlGenerator;

    private ObjectProphecy|ArticleRepositoryInterface $repository;

    private ArticleListener $listener;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->prophesize(CacheInterface::class);

        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->urlGenerator->route('articles.index')->willReturn('indexUrl');
        $this->urlGenerator->route('articles.rss')->willReturn('rssUrl');
        $this->urlGenerator->route('home')->willReturn('homeUrl');
        $this->urlGenerator->route('sitemap')->willReturn('sitemapUrl');

        $this->repository = $this->prophesize(ArticleRepositoryInterface::class);

        $this->listener = new ArticleListener(
            $this->cache->reveal(),
            $this->urlGenerator->reveal(),
            $this->repository->reveal()
        );
    }

    /** @test */
    public function itClearsCachesWhenAnArticleWasCreated(): void
    {
        $this->cache->forget('indexUrl')
            ->shouldBeCalled();

        $this->cache->forget('rssUrl')
            ->shouldBeCalled();

        $this->cache->forget('homeUrl')
            ->shouldBeCalled();

        $this->cache->forget('sitemapUrl')
            ->shouldBeCalled();

        $this->listener->handle(new ArticleWasCreated());
    }

    /** @test */
    public function itClearsCachesWhenAnArticleWasUpdated(): void
    {
        $uuid = 'my-uuid';
        $article = $this->getArticle([
            'uuid' => $uuid,
        ]);

        $this->repository->getByUuid($article->uuid())
            ->willReturn($article);

        $this->urlGenerator->route('articles.show', [
                'uuid' => $article->uuid(),
                'slug' => $article->slug(),
            ])
            ->willReturn('articleUrl');

        $this->cache->forget('articleUrl')
            ->shouldBeCalled();

        $this->cache->forget('indexUrl')
            ->shouldBeCalled();

        $this->cache->forget('rssUrl')
            ->shouldBeCalled();

        $this->cache->forget('homeUrl')
            ->shouldBeCalled();

        $this->cache->forget('sitemapUrl')
            ->shouldBeCalled();

        $this->listener->handle(new ArticleWasUpdated($uuid));
    }
}
