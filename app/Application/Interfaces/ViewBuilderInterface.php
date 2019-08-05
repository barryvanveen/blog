<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface ViewBuilderInterface
{
    public function render(string $view, array $data = []): string;
}
