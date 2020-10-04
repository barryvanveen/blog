<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Comments\Commands\CreateComment;
use App\Application\Comments\Commands\UpdateComment;
use App\Application\Comments\CommentRepository;
use App\Application\Comments\Handlers\CreateCommentHandler;
use App\Application\Comments\Handlers\UpdateCommentHandler;
use App\Application\Comments\ModelMapperInterface;
use App\Application\Interfaces\CommandBusInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Comments\Requests\AdminCommentCreateRequestInterface;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use App\Infrastructure\Eloquent\CommentMapper;
use App\Infrastructure\Http\Requests\AdminCommentCreateRequest;
use Illuminate\Support\ServiceProvider;

class CommentsServiceProvider extends ServiceProvider
{
    public function boot(
        CommandBusInterface $commandBus
    ): void {
        $commandBus->subscribe(CreateComment::class, CreateCommentHandler::class);
        $commandBus->subscribe(UpdateComment::class, UpdateCommentHandler::class);
    }

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
        $this->app->bind(AdminCommentCreateRequestInterface::class, AdminCommentCreateRequest::class);
    }
}
