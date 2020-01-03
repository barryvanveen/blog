<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\FilesystemInterface;
use Illuminate\Contracts\Filesystem\Filesystem;

class LaravelFilesystem implements FilesystemInterface
{
    /** @var Filesystem */
    private $laravelFilesystem;

    public function __construct(Filesystem $laravelFilesystem)
    {
        $this->laravelFilesystem = $laravelFilesystem;
    }

    public function getDriver(): \League\Flysystem\FilesystemInterface
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        return $this->laravelFilesystem->getDriver();
    }
}
