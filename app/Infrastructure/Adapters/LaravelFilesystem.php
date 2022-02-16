<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\FilesystemInterface;
use Illuminate\Contracts\Filesystem\Factory;
use League\Flysystem\Filesystem;

class LaravelFilesystem implements FilesystemInterface
{
    private Factory $laravelFilesystemFactory;

    public function __construct(
        Factory $laravelFilesystemFactory,
    ) {
        $this->laravelFilesystemFactory = $laravelFilesystemFactory;
    }

    /**
     * @psalm-suppress InvalidReturnType
     */
    public function getDriver(): Filesystem
    {
        /** @psalm-suppress InvalidReturnStatement */
        return $this->laravelFilesystemFactory->disk();
    }
}
