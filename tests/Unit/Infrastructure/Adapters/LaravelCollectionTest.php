<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelCollection;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelCollection
 */
class LaravelCollectionTest extends TestCase
{
    /** @test */
    public function itMakesACollection(): void
    {
        $items = [
            '123',
            '234',
            '345',
        ];

        $collection = LaravelCollection::make($items);

        $this->assertInstanceOf(LaravelCollection::class, $collection);
    }

    /** @test */
    public function itReturnsAllItems(): void
    {
        $items = [
            '123',
            '234',
            '345',
        ];

        $collection = LaravelCollection::make($items);

        $this->assertCount(3, $collection->all());
    }

    /** @test */
    public function itReturnsCount(): void
    {
        $items = [
            '123',
            '234',
            '345',
        ];

        $collection = LaravelCollection::make($items);

        $this->assertEquals(3, $collection->count());
    }

    /** @test */
    public function itReturnsIfCollectionIsEmptyOrNot(): void
    {
        $items = [
            '123',
            '234',
            '345',
        ];

        $emptyCollection = LaravelCollection::make();
        $nonEmptyCollection = LaravelCollection::make($items);

        $this->assertTrue($emptyCollection->isEmpty());
        $this->assertFalse($emptyCollection->isNotEmpty());
        $this->assertFalse($nonEmptyCollection->isEmpty());
        $this->assertNotFalse($nonEmptyCollection->isNotEmpty());
    }

    /** @test */
    public function itReturnsArray(): void
    {
        $items = [
            '123',
            '234',
            '345',
        ];

        $array = LaravelCollection::make($items)->toArray();

        $this->assertEquals([
            '123',
            '234',
            '345',
        ], $array);
    }
}
