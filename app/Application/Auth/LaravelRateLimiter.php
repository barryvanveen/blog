<?php

declare(strict_types=1);

namespace App\Application\Auth;

use Illuminate\Cache\RateLimiter;

class LaravelRateLimiter extends RateLimiter implements RateLimiterInterface
{

}
