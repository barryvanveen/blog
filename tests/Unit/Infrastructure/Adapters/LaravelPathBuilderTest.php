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

    public static function publicPathDataProvider()
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

    /**
     * @test
     *
     * @dataProvider assetPathDataProvider
     */
    public function itExtendsTheAssetPath($postfix, $expected): void
    {
        /** @var ObjectProphecy|Application $application */
        $application = $this->prophesize(Application::class);

        $application
            ->make(Argument::type('string'))
            ->willReturn('/my/public/path');

        $laravelPathBuilder = new LaravelPathBuilder(
            $application->reveal()
        );

        $this->assertEquals($expected, $laravelPathBuilder->assetPath($postfix));
    }

    public static function assetPathDataProvider()
    {
        return [
            [
                'manifest.json',
                '/my/public/path/dist/manifest.json',
            ],
            [
                '/manifest.json',
                '/my/public/path/dist/manifest.json',
            ],
            [
                'dist/manifest.json',
                '/my/public/path/dist/dist/manifest.json',
            ],
            [
                '/dist/manifest.json',
                '/my/public/path/dist/dist/manifest.json',
            ],
            [
                '../dist/manifest.json',
                '/my/public/path/dist/../dist/manifest.json',
            ],
            [
                '/../dist/manifest.json',
                '/my/public/path/dist/../dist/manifest.json',
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider storagePathDataProvider
     */
    public function itExtendsTheStoragePath($postfix, $expected): void
    {
        /** @var ObjectProphecy|Application $application */
        $application = $this->prophesize(Application::class);

        $application
            ->make(Argument::type('string'))
            ->willReturn('/my/storage/path');

        $laravelPathBuilder = new LaravelPathBuilder(
            $application->reveal()
        );

        $this->assertEquals($expected, $laravelPathBuilder->storagePath($postfix));
    }

    public static function storagePathDataProvider()
    {
        return [
            [
                'manifest.json',
                '/my/storage/path/manifest.json',
            ],
            [
                '/manifest.json',
                '/my/storage/path/manifest.json',
            ],
            [
                'dist/manifest.json',
                '/my/storage/path/dist/manifest.json',
            ],
            [
                '/dist/manifest.json',
                '/my/storage/path/dist/manifest.json',
            ],
            [
                '../dist/manifest.json',
                '/my/storage/path/../dist/manifest.json',
            ],
            [
                '/../dist/manifest.json',
                '/my/storage/path/../dist/manifest.json',
            ],
        ];
    }
}
