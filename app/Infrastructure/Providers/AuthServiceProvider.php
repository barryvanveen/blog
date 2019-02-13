<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Commands\Logout;
use App\Application\Auth\Handlers\LoginHandler;
use App\Application\Auth\Handlers\LogoutHandler;
use App\Application\Core\CommandBusInterface;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @param CommandBusInterface $commandBus
     */
    public function boot(CommandBusInterface $commandBus)
    {
        $commandBus->subscribe(Login::class, LoginHandler::class);
        $commandBus->subscribe(Logout::class, LogoutHandler::class);
    }
}
