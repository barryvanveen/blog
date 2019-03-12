<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Core\CommandBusInterface;
use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\RateLimiterInterface;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\TranslatorInterface;
use App\Infrastructure\Adapters\LaravelGuard;
use App\Infrastructure\Adapters\LaravelRateLimiter;
use App\Infrastructure\Adapters\LaravelSession;
use App\Infrastructure\Adapters\LaravelTranslator;
use App\Infrastructure\CommandBus\LaravelCommandBus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBusInterface::class, LaravelCommandBus::class);
    }

    public function boot(): void
    {
        $this->app->bind(GuardInterface::class, LaravelGuard::class);
        $this->app->bind(SessionInterface::class, LaravelSession::class);
        $this->app->bind(RateLimiterInterface::class, LaravelRateLimiter::class);
        $this->app->bind(TranslatorInterface::class, LaravelTranslator::class);
    }
}
