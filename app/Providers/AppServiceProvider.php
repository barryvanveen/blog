<?php

namespace App\Providers;

use App\Application\CommandBusInterface;
use App\Infrastructure\CommandBus\LaravelCommandBus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CommandBusInterface::class, LaravelCommandBus::class);
    }
}
