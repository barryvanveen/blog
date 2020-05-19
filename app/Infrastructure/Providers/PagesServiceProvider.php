<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Interfaces\QueryBuilderInterface;
use App\Application\Pages\ModelMapperInterface;
use App\Application\Pages\PageRepository;
use App\Domain\Pages\PageRepositoryInterface;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use Illuminate\Support\ServiceProvider;

class PagesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(PageRepository::class)
            ->needs(QueryBuilderInterface::class)
            ->give(function () {
                return new LaravelQueryBuilder(
                    PageEloquentModel::query()
                );
            });

        $this->app->bind(ModelMapperInterface::class, PageMapper::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
    }
}
