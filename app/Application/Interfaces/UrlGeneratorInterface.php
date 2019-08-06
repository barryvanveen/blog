<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface UrlGeneratorInterface
{
    public function route(string $name, array $parameters = [], bool $absolute = true): string;
}
