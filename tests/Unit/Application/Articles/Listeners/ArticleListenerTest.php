<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\Listeners;

use App\Application\Articles\Events\ArticleWasCreated;
use App\Application\Articles\Events\ArticleWasUpdated;
use App\Application\Articles\Listeners\ArticleListener;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use DateTimeImmutable;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\Events\ArticleWasCreated
 * @covers \App\Application\Articles\Events\ArticleWasUpdated
 * @covers \App\Application\Articles\Listeners\ArticleListener
 */
class ArticleListenerTest extends TestCase
{
    /** @var ObjectProphecy|CacheInterface */
    private $cache;

    /** @var ObjectProphecy|UrlGeneratorInterface */
    private $urlGenerator;

    /** @var ObjectProphecy|ArticleRepositoryInterface */
    private $repository;

    /** @var ArticleListener */
    private $listener;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->prophesize(CacheInterface::class);

        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->urlGenerator->route('articles.index')->willReturn('indexUrl');
        $this->urlGenerator->route('articles.rss')->willReturn('rssUrl');

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

        $this->listener->handle(new ArticleWasCreated());
    }

    /** @test */
    public function itClearsCachesWhenAnArticleWasUpdated(): void
    {
        $article = new Article(
            'foo',
            'foo',
            new DateTimeImmutable(),
            'my-slug',
            ArticleStatus::published(),
            'foo',
            'my-uuid'
        );

        $this->repository->getByUuid($article->uuid())
            ->willReturn($article);

        $this->urlGenerator->route('articles.show', [
                'uuid' => $article->uuid(),
                'slug' => $article->slug(),
            ])
            ->willReturn('articleUrl');

        $this->cache->forget('articleUrl')
            ->shouldBeCalled();

        $this->listener->handle(new ArticleWasUpdated('my-uuid'));
    }
}
