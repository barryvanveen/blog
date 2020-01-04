<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelConfiguration;
use Illuminate\Contracts\Config\Repository;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelConfiguration
 */
class LaravelConfigurationTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider stringDataProvider
     */
    public function itGetsConfigurationStrings($value, $expected): void
    {
        /** @var ObjectProphecy|Repository $repository */
        $repository = $this->prophesize(Repository::class);

        $repository
            ->get(Argument::type('string'), Argument::exact(''))
            ->willReturn($value);

        $configuration = new LaravelConfiguration($repository->reveal());

        $this->assertEquals($expected, $configuration->string('myKey'));
    }

    public function stringDataProvider()
    {
        return [
            [
                1,
                '1',
            ],
            [
                'myValue',
                'myValue',
            ],
            [
                '',
                '',
            ],
            [
                null,
                '',
            ],
        ];
    }
}
