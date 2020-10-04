<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\AdminArticlesCreatePresenter;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\AdminArticlesCreatePresenter
 */
class AdminArticlesCreatePresenterTest extends TestCase
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

        $presenter = new AdminArticlesCreatePresenter(
            $urlGenerator->reveal(),
            $session->reveal()
        );

        $this->assertEquals([
            'title' => 'New article',
            'store_url' => 'http://myurl',
            'statuses' => [
                '0' => [
                    'value' => '0',
                    'title' => 'Not published',
                ],
                '1' => [
                    'value' => '1',
                    'title' => 'Published',
                ],
            ],
            'article' => [
                'title' => 'old title',
                'published_at' => '',
                'description' => '',
                'content' => '',
                'status' => '',
            ],
        ], $presenter->present());
    }
}
