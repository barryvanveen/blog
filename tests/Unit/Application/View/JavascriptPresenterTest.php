<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\AssetUrlBuilderInterface;
use App\Application\View\JavascriptPresenter;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\View\JavascriptPresenter
 */
class JavascriptPresenterTest extends TestCase
{
    /** @var RouterInterface|ObjectProphecy */
    private $router;

    /** @var JavascriptPresenter */
    private $presenter;

    public function setUp(): void
    {
        parent::setUp();

        /** @var RouterInterface|ObjectProphecy $router */
        $this->router = $this->prophesize(RouterInterface::class);

        /** @var ObjectProphecy|AssetUrlBuilderInterface $assetBuilder */
        $assetBuilder = $this->prophesize(AssetUrlBuilderInterface::class);
        $assetBuilder->get(Argument::any())->willReturnArgument(0);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::any())->willReturnArgument(0);

        $this->presenter = new JavascriptPresenter(
            $this->router->reveal(),
            $assetBuilder->reveal(),
            $urlGenerator->reveal()
        );
    }

    /** @test */
    public function itOutputsNonAdminVariables(): void
    {
        $this->router
            ->currentRouteIsAdminRoute()
            ->willReturn(false);

        $this->assertEquals([
            'js_variables' => [
            ],
            'js_paths' => [
                'app.js',
            ],
        ], $this->presenter->present());
    }

    /** @test */
    public function itOutputsAdminVariables(): void
    {
        $this->router
            ->currentRouteIsAdminRoute()
            ->willReturn(true);

        $this->assertEquals([
            'js_variables' => [
                'markdown_to_html_url' => 'admin.markdown-to-html',
            ],
            'js_paths' => [
                'app.js',
                'admin.js',
            ],
        ], $this->presenter->present());
    }
}
