<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\ViewBuilderInterface;
use Illuminate\Contracts\View\Factory;

class LaravelViewBuilder implements ViewBuilderInterface
{
    public function __construct(
        private Factory $laravelViewFactory,
    ) {
    }

    public function render(string $view, array $data = []): string
    {
        return $this->laravelViewFactory->make($view, $data)->render();
    }
}
