<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector;

abstract class Controller extends BaseController
{
    /** @var Factory */
    protected $viewFactory;

    /** @var Redirector */
    protected $redirector;

    public function __construct(Factory $viewFactory, Redirector $redirector)
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }
}
