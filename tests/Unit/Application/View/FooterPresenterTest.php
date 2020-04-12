<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\FooterPresenter;
use App\Domain\Utils\MenuItem;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\View\FooterPresenter
 */
class FooterPresenterTest extends TestCase
{
    /** @test */
    public function itPassesTheCorrectVariables(): void
    {
        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'))->willReturn('/myroute');

        $presenter = new FooterPresenter(
            $urlGenerator->reveal()
        );

        $values = $presenter->present();

        $this->assertArrayHasKey('menu_items', $values);
        foreach ($values['menu_items'] as $item) {
            $this->assertInstanceOf(MenuItem::class, $item);
        }
    }
}
