<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\CacheInterface;
use Illuminate\Contracts\Cache\Repository;

class LaravelCache implements CacheInterface
{
    /** @var Repository */
    private $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }

    public function get(string $key)
    {
        return $this->cache->get($key);
    }

    public function put(string $key, $value, int $ttl): bool
    {
        return $this->cache->put($key, $value, $ttl);
    }

    public function forget(string $key): void
    {
        $this->cache->forget($key);
    }

    public function clear(): void
    {
        $this->cache->clear();
    }
}
