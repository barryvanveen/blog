<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\HeaderPresenter;
use App\Domain\Menu\MenuItem;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\View\HeaderPresenter
 */
class HeaderPresenterTest extends TestCase
{
    /** @test */
    public function itPassesTheCorrectVariables(): void
    {
        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'))->willReturn('/myroute');

        $presenter = new HeaderPresenter(
            $urlGenerator->reveal()
        );

        $values = $presenter->present();

        $this->assertArrayHasKey('home_url', $values);
        $this->assertArrayHasKey('menu_items', $values);
        foreach ($values['menu_items'] as $item) {
            $this->assertInstanceOf(MenuItem::class, $item);
        }
    }
}
