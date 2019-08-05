<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\UrlGeneratorInterface;
use Illuminate\Contracts\Routing\UrlGenerator;

class LaravelUrlGenerator implements UrlGeneratorInterface
{
    /** @var UrlGenerator */
    private $laravelUrlGenerator;

    public function __construct(UrlGenerator $laravelUrlGenerator)
    {
        $this->laravelUrlGenerator = $laravelUrlGenerator;
    }

    public function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return $this->laravelUrlGenerator->route($name, $parameters, $absolute);
    }
}
