<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Domain\Articles\Requests\AdminMarkdownToHtmlRequestInterface;
use App\Infrastructure\Http\Requests\AdminMarkdownToHtmlRequest;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AdminMarkdownToHtmlRequestInterface::class, AdminMarkdownToHtmlRequest::class);
    }
}
