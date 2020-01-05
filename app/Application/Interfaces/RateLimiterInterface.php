<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface RateLimiterInterface
{
    public function tooManyAttempts(string $key, int $maxAttempts): bool;
    public function hit(string $key, int $decayInSeconds = 60): int;
    public function clear(string $key): void;
    public function availableIn(string $key): int;
}
