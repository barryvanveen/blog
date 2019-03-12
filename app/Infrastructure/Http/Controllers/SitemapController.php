<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Illuminate\Contracts\View\View;

class SitemapController extends Controller
{
    public function index(): View
    {
        return $this->viewFactory->make('pages.sitemap');
    }
}
