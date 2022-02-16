<?php

declare(strict_types=1);

namespace App\Infrastructure\View;

use App\Application\View\PresenterInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\View;

class PresenterComposer
{
    /** @var Application */
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function compose(View $view): void
    {
        if (($html = @file_get_contents($view->getPath())) === false) {
            throw PresenterComposerException::becauseFileCouldNotBeFound($view->getPath());
        }

        $match = preg_match('/^@presenter\((\S+)\)/i', $html, $matches);

        if ($match !== 1) {
            return;
        }

        $presenter = $this->application->make($matches[1]);

        if (! $presenter instanceof PresenterInterface) {
            throw PresenterComposerException::becausePresenterDoesNotImplementInterface(get_class($presenter));
        }

        $view->with($presenter->present());
    }
}
