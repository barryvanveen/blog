<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

class ProjectsController extends Controller
{
    public function index()
    {
        return view('pages.projects');
    }
}
