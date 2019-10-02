<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface ConfigurationInterface
{
    public function get(string $key);
}
