<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface FilesystemInterface
{
    public function getDriver(): \League\Flysystem\FilesystemInterface;
}
