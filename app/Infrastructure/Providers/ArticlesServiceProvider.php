<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\Commands\UpdateArticle;
use App\Application\Articles\Handlers\CreateArticleHandler;
use App\Application\Articles\Handlers\UpdateArticleHandler;
use App\Application\Interfaces\CommandBusInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Requests\AdminArticleCreateRequestInterface;
use App\Domain\Articles\Requests\AdminArticleEditRequestInterface;
use App\Domain\Articles\Requests\AdminArticleUpdateRequestInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use App\Infrastructure\Http\Requests\AdminArticleCreateRequest;
use App\Infrastructure\Http\Requests\AdminArticleEditRequest;
use App\Infrastructure\Http\Requests\AdminArticleUpdateRequest;
use App\Infrastructure\Http\Requests\ArticleShowRequest;
use Illuminate\Support\ServiceProvider;

class ArticlesServiceProvider extends ServiceProvider
{
    public function boot(CommandBusInterface $commandBus): void
    {
        $commandBus->subscribe(CreateArticle::class, CreateArticleHandler::class);
        $commandBus->subscribe(UpdateArticle::class, UpdateArticleHandler::class);
    }

    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(ArticleShowRequestInterface::class, ArticleShowRequest::class);
        $this->app->bind(AdminArticleCreateRequestInterface::class, AdminArticleCreateRequest::class);
        $this->app->bind(AdminArticleEditRequestInterface::class, AdminArticleEditRequest::class);
        $this->app->bind(AdminArticleUpdateRequestInterface::class, AdminArticleUpdateRequest::class);
    }
}
