<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Infrastructure\View\PresenterComposer;
use App\Infrastructure\View\PresenterDirective;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', PresenterComposer::class);

        Blade::directive('presenter', new PresenterDirective());
    }
}
