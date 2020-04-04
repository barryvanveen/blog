<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Psr7ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResponseFactoryInterface::class, Psr17Factory::class);
        $this->app->bind(StreamFactoryInterface::class, Psr17Factory::class);
    }
}
