<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\AssetUrlBuilderInterface;
use App\Application\View\HeadHtmlPresenter;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\View\HeadHtmlPresenter
 */
class HeadHtmlPresenterTest extends TestCase
{
    private RouterInterface|ObjectProphecy $router;

    private HeadHtmlPresenter $presenter;

    public function setUp(): void
    {
        parent::setUp();

        /** @var ObjectProphecy|AssetUrlBuilderInterface $assetBuilder */
        $assetBuilder = $this->prophesize(AssetUrlBuilderInterface::class);
        $assetBuilder->get(Argument::any())->willReturnArgument(0);

        /** @var ObjectProphecy|ConfigurationInterface $configuration */
        $configuration = $this->prophesize(ConfigurationInterface::class);
        $configuration->string(Argument::exact('app.url'))->willReturn('http://myapp.dev');

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://myapp.dev/about');

        $this->router = $this->prophesize(RouterInterface::class);

        $this->presenter = new HeadHtmlPresenter(
            $assetBuilder->reveal(),
            $configuration->reveal(),
            $urlGenerator->reveal(),
            $this->router->reveal()
        );
    }

    /** @test */
    public function itPassesNoneAdminVariables(): void
    {
        $this->router->currentRouteIsAdminRoute()->willReturn(false);

        $this->assertEquals([
            'base_url' => 'http://myapp.dev',
            'css_paths' => [
                'app.css',
            ],
            'about_url' => 'http://myapp.dev/about',
            'rss_url' => 'http://myapp.dev/about',
        ], $this->presenter->present());
    }

    /** @test */
    public function itPasseszAdminVariables(): void
    {
        $this->router->currentRouteIsAdminRoute()->willReturn(true);

        $this->assertEquals([
            'base_url' => 'http://myapp.dev',
            'css_paths' => [
                'app.css',
                'admin.css',
            ],
            'about_url' => 'http://myapp.dev/about',
            'rss_url' => 'http://myapp.dev/about',
        ], $this->presenter->present());
    }
}
