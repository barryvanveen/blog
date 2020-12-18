<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\View\AdminPagesCreatePresenter;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\View\AdminPagesCreatePresenter
 */
class AdminPagesCreatePresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'))->willReturn('http://myurl');

        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->oldInput('title', Argument::cetera())->willReturn('old title');
        $session->oldInput(Argument::cetera())->willReturn('');

        $presenter = new AdminPagesCreatePresenter(
            $urlGenerator->reveal(),
            $session->reveal()
        );

        $this->assertEquals([
            'title' => 'New page',
            'url' => 'http://myurl',
            'page' => [
                'title' => 'old title',
                'slug' => '',
                'description' => '',
                'content' => '',
            ],
        ], $presenter->present());
    }
}
