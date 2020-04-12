<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\RouterInterface;
use Illuminate\Routing\Router;

final class LaravelRouter implements RouterInterface
{
    /** @var Router */
    private $laravelRouter;

    public function __construct(Router $laravelRouter)
    {
        $this->laravelRouter = $laravelRouter;
    }

    public function currentRouteIsAdminRoute(): bool
    {
        return strpos($this->currentRouteName(), 'admin.') === 0;
    }

    private function currentRouteName(): string
    {
        return (string) $this->laravelRouter->currentRouteName();
    }
}
