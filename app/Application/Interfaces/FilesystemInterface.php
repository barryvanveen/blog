<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use League\Flysystem\Filesystem;

interface FilesystemInterface
{
    public function getDriver(): Filesystem;
}
