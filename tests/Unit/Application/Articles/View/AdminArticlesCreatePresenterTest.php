<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\AdminArticlesCreatePresenter;
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

        $presenter = new AdminArticlesCreatePresenter(
            $urlGenerator->reveal()
        );

        $this->assertEquals([
            'title' => 'New article',
            'create_article_url' => 'http://myurl',
            'statuses' => [
                '0' => [
                    'value' => '0',
                    'title' => 'Not published',
                    'checked' => true,
                ],
                '1' => [
                    'value' => '1',
                    'title' => 'Published',
                    'checked' => false,
                ],
            ],
        ], $presenter->present());
    }
}
