<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\ConfigurationInterface;
use Illuminate\Contracts\Config\Repository;

class LaravelConfiguration implements ConfigurationInterface
{
    /** @var Repository */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
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
