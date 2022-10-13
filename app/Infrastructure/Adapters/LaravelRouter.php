<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\RouterInterface;
use Illuminate\Routing\Router;

final class LaravelRouter implements RouterInterface
{
    public function __construct(private Router $laravelRouter)
    {
    }

    public function currentRouteIsAdminRoute(): bool
    {
        return str_starts_with($this->currentRouteName(), 'admin.');
    }

    private function currentRouteName(): string
    {
        return (string) $this->laravelRouter->currentRouteName();
    }
}
