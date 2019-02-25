<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\RateLimiterInterface;
use Illuminate\Cache\RateLimiter;

class LaravelRateLimiter implements RateLimiterInterface
{
    /** @var RateLimiter */
    private $laravelRateLimiter;

    public function __construct(RateLimiter $laravelRateLimiter)
    {
        $this->laravelRateLimiter = $laravelRateLimiter;
    }

    public function tooManyAttempts($key, $maxAttempts): bool
    {
        return $this->laravelRateLimiter->tooManyAttempts($key, $maxAttempts);
    }

    public function hit($key, $decayMinutes = 1): int
    {
        return $this->laravelRateLimiter->hit($key, $decayMinutes);
    }

    public function clear($key): void
    {
        $this->laravelRateLimiter->clear($key);
    }

    public function availableIn($key): int
    {
        return $this->laravelRateLimiter->availableIn($key);
    }
}
