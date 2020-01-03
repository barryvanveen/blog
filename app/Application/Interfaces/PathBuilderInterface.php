<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface PathBuilderInterface
{
    public function publicPath(string $path): string;

    public function assetPath(string $path): string;

    public function storagePath(string $path): string;
}
