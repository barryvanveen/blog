<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\SessionInterface;
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
    /** @var RouterInterface|ObjectProphecy */
    private $router;

    /** @var HeadHtmlPresenter */
    private $presenter;

    public function setUp(): void
    {
        parent::setUp();

        /** @var ObjectProphecy|AssetUrlBuilderInterface $assetBuilder */
        $assetBuilder = $this->prophesize(AssetUrlBuilderInterface::class);
        $assetBuilder->get(Argument::any())->willReturnArgument(0);

        /** @var ObjectProphecy|ConfigurationInterface $configuration */
        $configuration = $this->prophesize(ConfigurationInterface::class);
        $configuration->string(Argument::exact('app.url'))->willReturn('http://myapp.dev');

        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->token()->willReturn('myMockToken');

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://myapp.dev/about');

        /** @var RouterInterface|ObjectProphecy $router */
        $this->router = $this->prophesize(RouterInterface::class);

        $this->presenter = new HeadHtmlPresenter(
            $assetBuilder->reveal(),
            $configuration->reveal(),
            $session->reveal(),
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
            'csrf_token' => 'myMockToken',
            'css_paths' => [
                'app.css',
            ],
            'about_url' => 'http://myapp.dev/about',
        ], $this->presenter->present());
    }

    /** @test */
    public function itPasseszAdminVariables(): void
    {
        $this->router->currentRouteIsAdminRoute()->willReturn(true);

        $this->assertEquals([
            'base_url' => 'http://myapp.dev',
            'csrf_token' => 'myMockToken',
            'css_paths' => [
                'app.css',
                'admin.css',
            ],
            'about_url' => 'http://myapp.dev/about',
        ], $this->presenter->present());
    }
}
