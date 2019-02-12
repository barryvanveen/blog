<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Core\CommandBusInterface;
use App\Infrastructure\CommandBus\LaravelCommandBus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CommandBusInterface::class, LaravelCommandBus::class);
    }
}
