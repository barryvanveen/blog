<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Http\Middleware;

use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\ConfigurationInterface;
use App\Infrastructure\Http\Middleware\CacheResponseMiddleware;
use Closure;
use Fig\Http\Message\RequestMethodInterface;
use Illuminate\Http\Request;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Http\Middleware\CacheResponseMiddleware
 */
class CacheResponseMiddlewareTest extends TestCase
{
    private ObjectProphecy|CacheInterface $cache;

    private ObjectProphecy|ConfigurationInterface $config;

    private ObjectProphecy|Request $request;

    private Closure $next;

    private CacheResponseMiddleware $middleware;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->prophesize(CacheInterface::class);
        $this->config = $this->prophesize(ConfigurationInterface::class);

        $this->request = $this->prophesize(Request::class);
        $this->next = fn() => new Response('nextResponse');

        $this->middleware = new CacheResponseMiddleware(
            $this->cache->reveal(),
            $this->config->reveal()
        );
    }

    /** @test */
    public function itDoesNotCacheIfCacheIsDisabled(): void
    {
        // arrange
        $this->config->boolean('cache.cache_responses')
            ->willReturn(false);

        $this->cache->has(Argument::any())
            ->shouldNotBeCalled();

        // act
        $result = $this->middleware->handle(
            $this->request->reveal(),
            $this->next
        );

        // assert
        $this->assertEquals('nextResponse', (string) $result->getContent());
    }

    /** @test */
    public function itDoesNotCacheIfRequestIsPost(): void
    {
        // arrange
        $this->config->boolean('cache.cache_responses')
            ->willReturn(true);

        $this->request->method()
            ->willReturn(RequestMethodInterface::METHOD_POST);

        $this->cache->has(Argument::any())
            ->shouldNotBeCalled();

        // act
        $result = $this->middleware->handle(
            $this->request->reveal(),
            $this->next
        );

        // assert
        $this->assertEquals('nextResponse', (string) $result->getContent());
    }

    /** @test */
    public function itReturnsCachedResponseIfItExists(): void
    {
        // arrange
        $this->mockCacheableRequest();

        $url = 'myurl';

        $this->request->url()
            ->willReturn($url);

        $this->cache->has($url)
            ->willReturn(true);

        $this->cache->get($url)
            ->shouldBeCalled()
            ->willReturn('cacheResponse');

        // act
        $result = $this->middleware->handle(
            $this->request->reveal(),
            $this->next
        );

        // assert
        $this->assertEquals('cacheResponse', $result);
    }

    /** @test */
    public function itCachesUncachedResponsesWithDefaultTtl(): void
    {
        // arrange
        $this->mockCacheableRequest();

        $url = 'myurl';
        $defaultTtl = 604800;

        $this->request->url()
            ->willReturn($url);

        $this->cache->has($url)
            ->willReturn(false);

        $this->cache->put($url, Argument::any(), $defaultTtl)
            ->shouldBeCalled();

        // act
        $result = $this->middleware->handle(
            $this->request->reveal(),
            $this->next
        );

        // assert
        $this->assertEquals('nextResponse', (string) $result->getContent());
    }

    /** @test */
    public function itCachesUncachedResponsesWithCustomTtl(): void
    {
        // arrange
        $this->mockCacheableRequest();

        $url = 'myurl';
        $customTtl = 123;

        $this->request->url()
            ->willReturn($url);

        $this->cache->has($url)
            ->willReturn(false);

        $this->cache->put($url, Argument::any(), $customTtl)
            ->shouldBeCalled();

        // act
        $result = $this->middleware->handle(
            $this->request->reveal(),
            $this->next,
            $customTtl,
        );

        // assert
        $this->assertEquals('nextResponse', (string) $result->getContent());
    }

    /** @test */
    public function itDoesNotCacheUnsuccessfulResponses(): void
    {
        // arrange
        $this->mockCacheableRequest();

        $url = 'myurl';

        $this->request->url()
            ->willReturn($url);

        $this->cache->has($url)
            ->willReturn(false);

        $this->next = fn() => new Response('errorResponse', 500);

        $this->cache->put($url, Argument::any(), Argument::any())
            ->shouldNotBeCalled();

        // act
        $result = $this->middleware->handle(
            $this->request->reveal(),
            $this->next
        );

        // assert
        $this->assertEquals('errorResponse', (string) $result->getContent());
    }

    private function mockCacheableRequest(): void
    {
        $this->config->boolean('cache.cache_responses')
            ->willReturn(true);

        $this->request->method()
            ->willReturn(RequestMethodInterface::METHOD_GET);
    }
}
