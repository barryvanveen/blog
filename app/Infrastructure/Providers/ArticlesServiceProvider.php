<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\Handlers\CreateArticleHandler;
use App\Application\Core\CommandBusInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ArticlesServiceProvider extends ServiceProvider
{
    public function boot(CommandBusInterface $commandBus): void
    {
        $commandBus->subscribe(CreateArticle::class, CreateArticleHandler::class);
    }

    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }
}
