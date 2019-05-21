<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelSlugFactory;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelSlugFactory
 */
class LaravelSlugFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider slugDataProvider
     * @param string $original
     * @param string $expected
     */
    public function itCreatesSlugsFromStrings(string $original, string $expected): void
    {
        // arrange
        $slugFactory = new LaravelSlugFactory();

        // act
        $slug = $slugFactory->slug($original);

        // assert
        $this->assertEquals($expected, $slug);
    }

    public function slugDataProvider()
    {
        return [
            ['Title', 'title'],
            ['Title Foo', 'title-foo'],
            ['Title@Foo', 'title-at-foo'],
            ['Title    Foo', 'title-foo'],
            ['Title$%^Foo', 'titlefoo'],
        ];
    }
}
