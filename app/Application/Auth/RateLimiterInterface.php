<?php

declare(strict_types=1);

namespace App\Application\Auth;

interface RateLimiterInterface
{
    public function tooManyAttempts($key, $maxAttempts);
    public function hit($key, $decayMinutes = 1);
    public function clear($key);
    public function availableIn($key);
}
