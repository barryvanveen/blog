<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Commands\Logout;
use App\Application\Auth\Handlers\LoginHandler;
use App\Application\Auth\Handlers\LogoutHandler;
use App\Application\Auth\Handlers\RateLimitedLoginHandler;
use App\Application\Auth\Requests\LoginRequestInterface;
use App\Application\Core\CommandBusInterface;
use App\Application\Interfaces\RateLimiterInterface;
use App\Infrastructure\Http\Requests\LoginRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(CommandBusInterface $commandBus): void
    {
        $this->app->bind(RateLimitedLoginHandler::class, function (Application $app): RateLimitedLoginHandler {
            return new RateLimitedLoginHandler(
                $app->make(LoginHandler::class),
                $app->make(RateLimiterInterface::class)
            );
        });

        $this->app->bind(LoginRequestInterface::class, LoginRequest::class);

        $commandBus->subscribe(Login::class, RateLimitedLoginHandler::class);
        $commandBus->subscribe(Logout::class, LogoutHandler::class);
    }
}
