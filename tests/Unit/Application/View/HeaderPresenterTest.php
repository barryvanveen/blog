<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\HeaderPresenter;
use App\Domain\Utils\MenuItem;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\View\HeaderPresenter
 */
class HeaderPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheMenuForVisitors(): void
    {
        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::exact('home'))->willReturn('/homeroute')
            ->shouldBeCalled();
        $urlGenerator->route(Argument::type('string'))->willReturn('/myroute');

        /** @var ObjectProphecy|RouterInterface $router */
        $router = $this->prophesize(RouterInterface::class);
        $router->currentRouteIsAdminRoute()->willReturn(false)->shouldBeCalled();

        $presenter = new HeaderPresenter(
            $urlGenerator->reveal(),
            $router->reveal()
        );

        $values = $presenter->present();

        $this->assertArrayHasKey('home_url', $values);

        $this->assertArrayHasKey('menu_items', $values);
        foreach ($values['menu_items'] as $item) {
            $this->assertInstanceOf(MenuItem::class, $item);
        }
    }

    /** @test */
    public function itPresentsTheMenuForTheAdminSection(): void
    {
        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::exact('admin.dashboard'))->willReturn('/homeroute')
            ->shouldBeCalled();
        $urlGenerator->route(Argument::type('string'))->willReturn('/myroute');

        /** @var ObjectProphecy|RouterInterface $router */
        $router = $this->prophesize(RouterInterface::class);
        $router->currentRouteIsAdminRoute()->willReturn(true)->shouldBeCalled();

        $presenter = new HeaderPresenter(
            $urlGenerator->reveal(),
            $router->reveal()
        );

        $values = $presenter->present();

        $this->assertArrayHasKey('home_url', $values);
        $this->assertArrayHasKey('menu_items', $values);
        foreach ($values['menu_items'] as $item) {
            $this->assertInstanceOf(MenuItem::class, $item);
        }
    }
}
