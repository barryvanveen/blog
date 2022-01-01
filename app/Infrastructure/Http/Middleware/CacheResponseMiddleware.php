<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Middleware;

use App\Application\Http\StatusCode;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\ConfigurationInterface;
use Closure;
use Fig\Http\Message\RequestMethodInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheResponseMiddleware
{
    private const TTL_ONE_HOUR = 3600;

    private CacheInterface $cache;
    private ConfigurationInterface $configuration;

    public function __construct(
        CacheInterface $cache,
        ConfigurationInterface $configuration
    ) {
        $this->cache = $cache;
        $this->configuration = $configuration;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->configuration->boolean('cache.cache_responses') === false ||
            $request->method() !== RequestMethodInterface::METHOD_GET
        ) {
            return $next($request);
        }

        $key = $request->url();

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        /** @var Response $response */
        $response = $next($request);

        if ($response instanceof Response &&
            $response->getStatusCode() === StatusCode::STATUS_OK) {
            $this->cache->put($key, $response, self::TTL_ONE_HOUR);
        }

        return $response;
    }
}
