<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Illuminate\Contracts\View\View;

class ProjectsController extends Controller
{
    public function index(): View
    {
        return $this->viewFactory->make('pages.projects');
    }
}
