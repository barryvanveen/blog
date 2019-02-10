<?php

namespace App\Infrastructure\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }
}
