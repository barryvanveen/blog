<?php

namespace App\Http\Controllers;

class SitemapController extends Controller
{
    public function index()
    {
        return view('pages.sitemap');
    }
}
