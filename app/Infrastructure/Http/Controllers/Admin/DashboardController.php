<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers\Admin;

use App\Infrastructure\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.admin.dashboard');
    }
}
