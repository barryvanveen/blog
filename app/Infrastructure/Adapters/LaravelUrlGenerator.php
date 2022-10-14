<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\UrlGeneratorInterface;
use Illuminate\Contracts\Routing\UrlGenerator;

class LaravelUrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private UrlGenerator $laravelUrlGenerator,
    ) {
    }

    public function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return $this->laravelUrlGenerator->route($name, $parameters, $absolute);
    }
}
