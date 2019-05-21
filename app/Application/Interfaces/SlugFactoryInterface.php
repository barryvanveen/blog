<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface SlugFactoryInterface
{
    public function slug(string $value): string;
}
