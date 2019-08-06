<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\ViewBuilderInterface;
use App\Infrastructure\Exceptions\ViewException;
use Illuminate\Contracts\View\Factory;

class LaravelViewBuilder implements ViewBuilderInterface
{
    /** @var Factory */
    private $laravelViewFactory;

    public function __construct(Factory $laravelViewFactory)
    {
        $this->laravelViewFactory = $laravelViewFactory;
    }

    public function render(string $view, array $data = []): string
    {
        $view = $this->laravelViewFactory->make($view, $data);

        $rendered = $view->render();

        if (is_string($rendered) === false) {
            throw ViewException::renderReturnsAnArrayInsteadOfString($rendered);
        }

        return $view->render();
    }
}
