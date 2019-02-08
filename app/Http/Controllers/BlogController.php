<?php

namespace App\Http\Controllers;

final class BlogController extends Controller
{
    public function index()
    {
        return view('pages.blog');
    }
}
