<?php

namespace App\Http\Controllers;

class ProjectsController extends Controller
{
    public function index()
    {
        return view('pages.projects');
    }
}
