<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\StreamFactory;

class Psr7ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResponseFactoryInterface::class, ResponseFactory::class);
        $this->app->bind(StreamFactoryInterface::class, StreamFactory::class);
    }
}
