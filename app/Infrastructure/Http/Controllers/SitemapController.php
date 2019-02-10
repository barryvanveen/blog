<?php

namespace App\Infrastructure\Http\Controllers;

class SitemapController extends Controller
{
    public function index()
    {
        return view('pages.sitemap');
    }
}
