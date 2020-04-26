<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface CacheInterface
{
    public function has(string $key): bool;

    public function get(string $key);

    public function put(string $key, $value, int $ttl);

    public function forget(string $key): void;
}
