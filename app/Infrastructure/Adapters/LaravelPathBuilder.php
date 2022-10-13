<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\PathBuilderInterface;
use Illuminate\Contracts\Foundation\Application;

class LaravelPathBuilder implements PathBuilderInterface
{
    public function __construct(private Application $application)
    {
    }

    public function publicPath(string $path): string
    {
        return $this->appendPath(
            $this->application->make('path.public'),
            $path
        );
    }

    public function assetPath(string $path): string
    {
        return $this->appendPath(
            $this->publicPath('dist'),
            $path
        );
    }

    public function storagePath(string $path): string
    {
        return $this->appendPath(
            $this->application->make('path.storage'),
            $path
        );
    }

    private function appendPath(string $base, string $path): string
    {
        return $base.($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}
