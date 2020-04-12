<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface RouterInterface
{
    public function currentRouteIsAdminRoute(): bool;
}
