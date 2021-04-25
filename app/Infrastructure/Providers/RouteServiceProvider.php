<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('comments', function (Request $request) {
            return [
                Limit::perMinute(config('throttling.comments.total')),
                Limit::perMinute(config('throttling.comments.individual'))->by($request->ip() ?? 'ip-placeholder'),
            ];
        });

        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(config('throttling.login.total')),
                Limit::perMinute(config('throttling.login.individual'))->by($request->ip() ?? 'ip-placeholder'),
            ];
        });
    }
}
