<?php

declare(strict_types=1);

namespace App\Application\View;

interface AssetUrlBuilderInterface
{
    public function get(string $path): string;
}
