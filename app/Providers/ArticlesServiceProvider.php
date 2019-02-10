<?php

namespace App\Providers;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\Handlers\CreateArticleHandler;
use App\Application\CommandBusInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ArticlesServiceProvider extends ServiceProvider
{
    /**
     * @param CommandBusInterface $commandBus
     */
    public function boot(CommandBusInterface $commandBus)
    {
        $commandBus->subscribe(CreateArticle::class, CreateArticleHandler::class);
    }

    public function register()
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }
}
