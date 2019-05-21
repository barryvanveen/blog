<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\SlugFactoryInterface;
use Illuminate\Support\Str;

class LaravelSlugFactory implements SlugFactoryInterface
{
    public function slug(string $value): string
    {
        return Str::slug($value);
    }
}
