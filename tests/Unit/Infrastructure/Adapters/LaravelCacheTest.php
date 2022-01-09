<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelCache;
use Illuminate\Contracts\Cache\Repository;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelCache
 */
class LaravelCacheTest extends TestCase
{
    /** @test */
    public function isChecksWhetherKeyExistsInCache(): void
    {
        /** @var Repository $repository */
        $repository = app()->make(Repository::class);
        $repository->set('foo', 'bar');

        $cache = new LaravelCache($repository);

        $this->assertEquals(true, $cache->has('foo'));
        $this->assertEquals(false, $cache->has('baz'));
    }

    /** @test */
    public function itGetsKeyFromCache(): void
    {
        /** @var Repository $repository */
        $repository = app()->make(Repository::class);
        $repository->set('foo', 'bar');

        $cache = new LaravelCache($repository);

        $this->assertEquals('bar', $cache->get('foo'));
        $this->assertEquals(null, $cache->has('baz'));
    }

    /** @test */
    public function itPutsKeyInCache(): void
    {
        /** @var Repository $repository */
        $repository = app()->make(Repository::class);
        $repository->set('foo', 'bar');

        $cache = new LaravelCache($repository);
        $cache->put('foo', 'baz', 60);
        $cache->put('baz', ['apple', 'pear'], 60);

        $this->assertEquals('baz', $cache->get('foo'));
        $this->assertEquals(['apple', 'pear'], $cache->get('baz'));
    }

    /** @test */
    public function itRemovesKeyFromCache(): void
    {
        /** @var Repository $repository */
        $repository = app()->make(Repository::class);
        $repository->set('foo', 'bar');

        $cache = new LaravelCache($repository);
        $cache->forget('foo');

        $this->assertEquals(null, $cache->get('foo'));
    }

    /** @test */
    public function itClearsAllItemsFromCache(): void
    {
        /** @var Repository $repository */
        $repository = app()->make(Repository::class);
        $repository->set('foo', 'bar');
        $repository->set('bar', 'baz');

        $cache = new LaravelCache($repository);
        $cache->clear();

        $this->assertEquals(null, $cache->get('foo'));
        $this->assertEquals(null, $cache->get('bar'));
    }
}
