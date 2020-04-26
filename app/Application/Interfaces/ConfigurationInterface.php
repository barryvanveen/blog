<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface ConfigurationInterface
{
    public function string(string $key): string;

    public function boolean(string $key): bool;
}
