<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\PathBuilderInterface;
use App\Application\View\AssetUrlBuilder;
use App\Application\View\ManifestException;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\View\AssetUrlBuilder
 * @covers \App\Application\View\ManifestException
 */
class AssetUrlBuilderTest extends TestCase
{
    /** @test */
    public function itFailsIfTheManifestFileCannotBeFound(): void
    {
        /** @var ObjectProphecy|PathBuilderInterface $pathBuilder */
        $pathBuilder = $this->prophesize(PathBuilderInterface::class);

        $pathBuilder
            ->assetPath(Argument::type('string'))
            ->willReturn(__DIR__.'/does_not_exist.lol');

        $assetUrlBuilder = new AssetUrlBuilder($pathBuilder->reveal());

        $this->expectException(ManifestException::class);
        $this->expectExceptionMessage('Could not read or decode manifest file.');

        $assetUrlBuilder->get('app.js');
    }

    /** @test */
    public function itFailsIfTheManifestFileDoesNotContainTheRequestedAsset(): void
    {
        /** @var ObjectProphecy|PathBuilderInterface $pathBuilder */
        $pathBuilder = $this->prophesize(PathBuilderInterface::class);

        $pathBuilder
            ->assetPath(Argument::type('string'))
            ->willReturn(__DIR__.'/manifest.json');

        $assetUrlBuilder = new AssetUrlBuilder($pathBuilder->reveal());

        $this->expectException(ManifestException::class);
        $this->expectExceptionMessage('Asset is missing from manifest file:');

        $assetUrlBuilder->get('doesNotExist.js');
    }

    /** @test */
    public function itReturnsTheRequestAssetUrl(): void
    {
        /** @var ObjectProphecy|PathBuilderInterface $pathBuilder */
        $pathBuilder = $this->prophesize(PathBuilderInterface::class);

        $pathBuilder
            ->assetPath(Argument::type('string'))
            ->willReturn(__DIR__.'/manifest.json');

        $assetUrlBuilder = new AssetUrlBuilder($pathBuilder->reveal());

        $this->assertEquals('./dist/app.6b9168bca06154fe691c.js', $assetUrlBuilder->get('app.js'));
    }
}
