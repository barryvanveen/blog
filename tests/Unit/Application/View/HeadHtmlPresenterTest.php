<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\SessionInterface;
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
    /** @test */
    public function itPassesTheCorrectVariables(): void
    {
        /** @var ObjectProphecy|AssetUrlBuilderInterface $assetBuilder */
        $assetBuilder = $this->prophesize(AssetUrlBuilderInterface::class);
        $assetBuilder->get(Argument::exact('app.css'))->willReturn('/path/to/app.css');

        /** @var ObjectProphecy|ConfigurationInterface $configuration */
        $configuration = $this->prophesize(ConfigurationInterface::class);
        $configuration->string(Argument::exact('app.url'))->willReturn('http://myapp.dev');

        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->token()->willReturn('myMockToken');

        $presenter = new HeadHtmlPresenter(
            $assetBuilder->reveal(),
            $configuration->reveal(),
            $session->reveal()
        );

        $this->assertEquals([
            'base_url' => 'http://myapp.dev',
            'csrf_token' => 'myMockToken',
            'css_path' => '/path/to/app.css',
        ], $presenter->present());
    }
}
