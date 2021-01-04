<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Interfaces\CommandBusInterface;
use App\Application\Interfaces\EventBusInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Application\Pages\Commands\CreatePage;
use App\Application\Pages\Commands\UpdatePage;
use App\Application\Pages\Events\PageWasUpdated;
use App\Application\Pages\Handlers\CreatePageHandler;
use App\Application\Pages\Handlers\UpdatePageHandler;
use App\Application\Pages\Listeners\PageListener;
use App\Application\Pages\ModelMapperInterface;
use App\Application\Pages\PageRepository;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Pages\Requests\AdminPageCreateRequestInterface;
use App\Domain\Pages\Requests\AdminPageEditRequestInterface;
use App\Domain\Pages\Requests\AdminPageUpdateRequestInterface;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use App\Infrastructure\Http\Requests\AdminPageCreateRequest;
use App\Infrastructure\Http\Requests\AdminPageEditRequest;
use App\Infrastructure\Http\Requests\AdminPageUpdateRequest;
use Illuminate\Support\ServiceProvider;

class PagesServiceProvider extends ServiceProvider
{
    public function boot(
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus
    ): void {
        $commandBus->subscribe(CreatePage::class, CreatePageHandler::class);
        $commandBus->subscribe(UpdatePage::class, UpdatePageHandler::class);

        $eventBus->subscribe(PageWasUpdated::class, PageListener::class);
    }

    public function register(): void
    {
        $this->app->when(PageRepository::class)
            ->needs(QueryBuilderInterface::class)
            ->give(function () {
                return new LaravelQueryBuilder(new PageEloquentModel());
            });

        $this->app->bind(ModelMapperInterface::class, PageMapper::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(AdminPageCreateRequestInterface::class, AdminPageCreateRequest::class);
        $this->app->bind(AdminPageEditRequestInterface::class, AdminPageEditRequest::class);
        $this->app->bind(AdminPageUpdateRequestInterface::class, AdminPageUpdateRequest::class);
    }
}
