<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelPathBuilder;
use Illuminate\Contracts\Foundation\Application;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelPathBuilder
 */
class LaravelPathBuilderTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider publicPathDataProvider
     */
    public function itExtendsThePublicPath($postfix, $expected): void
    {
        /** @var ObjectProphecy|Application $application */
        $application = $this->prophesize(Application::class);

        $application
            ->make(Argument::type('string'))
            ->willReturn('/my/public/path');

        $laravelPathBuilder = new LaravelPathBuilder(
            $application->reveal()
        );

        $this->assertEquals($expected, $laravelPathBuilder->publicPath($postfix));
    }

    public function publicPathDataProvider()
    {
        return [
            [
                'manifest.json',
                '/my/public/path/manifest.json',
            ],
            [
                '/manifest.json',
                '/my/public/path/manifest.json',
            ],
            [
                'dist/manifest.json',
                '/my/public/path/dist/manifest.json',
            ],
            [
                '/dist/manifest.json',
                '/my/public/path/dist/manifest.json',
            ],
            [
                '../dist/manifest.json',
                '/my/public/path/../dist/manifest.json',
            ],
            [
                '/../dist/manifest.json',
                '/my/public/path/../dist/manifest.json',
            ],
        ];
    }
}
