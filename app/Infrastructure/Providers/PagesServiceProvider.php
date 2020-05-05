<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Pages\ModelMapperInterface;
use App\Application\Pages\PageRepository;
use App\Domain\Pages\PageRepositoryInterface;
use App\Infrastructure\Eloquent\PageMapper;
use Illuminate\Support\ServiceProvider;

class PagesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ModelMapperInterface::class, PageMapper::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
    }
}
