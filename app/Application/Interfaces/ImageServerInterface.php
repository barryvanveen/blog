<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface ImageServerInterface
{
    public function outputImage(string $filename, array $options): ResponseInterface;
}
