<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Articles\ArticleRepository;
use App\Application\Interfaces\ModelMapperInterface;
use App\Infrastructure\Eloquent\ArticleMapper;
use Illuminate\Support\ServiceProvider;

class ModelMapperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(ArticleRepository::class)
            ->needs(ModelMapperInterface::class)
            ->give(function (): ArticleMapper {
                return new ArticleMapper();
            });
    }
}
