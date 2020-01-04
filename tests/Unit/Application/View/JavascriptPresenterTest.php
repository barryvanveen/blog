<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

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
    /** @test */
    public function itPassesTheCorrectVariables(): void
    {
        /** @var ObjectProphecy|AssetUrlBuilderInterface $assetBuilder */
        $assetBuilder = $this->prophesize(AssetUrlBuilderInterface::class);
        $assetBuilder->get(Argument::exact('app.js'))->willReturn('/path/to/app.js');

        $presenter = new JavascriptPresenter(
            $assetBuilder->reveal()
        );

        $this->assertEquals([
            'js_path' => '/path/to/app.js',
        ], $presenter->present());
    }
}
