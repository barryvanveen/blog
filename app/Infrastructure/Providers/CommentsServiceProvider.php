<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Comments\CommentRepository;
use App\Application\Comments\ModelMapperInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use App\Infrastructure\Eloquent\CommentMapper;
use Illuminate\Support\ServiceProvider;

class CommentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CommentRepository::class)
            ->needs(QueryBuilderInterface::class)
            ->give(function () {
                return new LaravelQueryBuilder(
                    CommentEloquentModel::query()
                );
            });

        $this->app->bind(ModelMapperInterface::class, CommentMapper::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
    }
}
