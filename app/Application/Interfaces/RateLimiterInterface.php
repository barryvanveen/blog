<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface RateLimiterInterface
{
    public function tooManyAttempts($key, $maxAttempts): bool;
    public function hit($key, $decayMinutes = 1): int;
    public function clear($key): void;
    public function availableIn($key): int;
}
