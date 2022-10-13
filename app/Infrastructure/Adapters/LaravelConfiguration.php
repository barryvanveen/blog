<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\ConfigurationInterface;
use Illuminate\Contracts\Config\Repository;

class LaravelConfiguration implements ConfigurationInterface
{
    public function __construct(
        private Repository $config,
    ) {
    }

    public function string(string $key): string
    {
        return (string) $this->config->get($key, '');
    }

    public function boolean(string $key): bool
    {
        return (bool) $this->config->get($key, false);
    }
}
