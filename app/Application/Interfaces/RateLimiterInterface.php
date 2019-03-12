<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface RateLimiterInterface
{
    public function tooManyAttempts(string $key, int $maxAttempts): bool;
    public function hit(string $key, int $decayMinutes = 1): int;
    public function clear(string $key): void;
    public function availableIn(string $key): int;
}
