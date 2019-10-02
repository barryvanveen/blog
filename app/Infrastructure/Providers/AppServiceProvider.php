<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\Core\CommandBusInterface;
use App\Application\Core\ResponseBuilder;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Core\UniqueIdGenerator;
use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\PathBuilderInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Application\Interfaces\RateLimiterInterface;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\SlugFactoryInterface;
use App\Application\Interfaces\TranslatorInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Interfaces\ViewBuilderInterface;
use App\Application\View\AssetUrlBuilder;
use App\Application\View\AssetUrlBuilderInterface;
use App\Domain\Core\UniqueIdGeneratorInterface;
use App\Infrastructure\Adapters\LaravelConfiguration;
use App\Infrastructure\Adapters\LaravelGuard;
use App\Infrastructure\Adapters\LaravelPathBuilder;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Adapters\LaravelRateLimiter;
use App\Infrastructure\Adapters\LaravelSession;
use App\Infrastructure\Adapters\LaravelSlugFactory;
use App\Infrastructure\Adapters\LaravelTranslator;
use App\Infrastructure\Adapters\LaravelUrlGenerator;
use App\Infrastructure\Adapters\LaravelViewBuilder;
use App\Infrastructure\CommandBus\LaravelCommandBus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AssetUrlBuilderInterface::class, AssetUrlBuilder::class);
        $this->app->singleton(CommandBusInterface::class, LaravelCommandBus::class);

        $this->app->bind(ConfigurationInterface::class, LaravelConfiguration::class);
        $this->app->bind(GuardInterface::class, LaravelGuard::class);
        $this->app->bind(PathBuilderInterface::class, LaravelPathBuilder::class);
        $this->app->bind(QueryBuilderInterface::class, LaravelQueryBuilder::class);
        $this->app->bind(RateLimiterInterface::class, LaravelRateLimiter::class);
        $this->app->bind(ResponseBuilderInterface::class, ResponseBuilder::class);
        $this->app->bind(SessionInterface::class, LaravelSession::class);
        $this->app->bind(SlugFactoryInterface::class, LaravelSlugFactory::class);
        $this->app->bind(TranslatorInterface::class, LaravelTranslator::class);
        $this->app->bind(UniqueIdGeneratorInterface::class, UniqueIdGenerator::class);
        $this->app->bind(UrlGeneratorInterface::class, LaravelUrlGenerator::class);
        $this->app->bind(ViewBuilderInterface::class, LaravelViewBuilder::class);
    }
}
