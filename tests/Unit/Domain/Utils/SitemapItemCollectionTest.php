<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Utils;

use App\Domain\Utils\SitemapItem;
use App\Domain\Utils\SitemapItemCollection;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * @covers \App\Domain\Utils\SitemapItemCollection
 * @covers \App\Domain\Utils\SitemapItem
 */
class SitemapItemCollectionTest extends TestCase
{
    /** @test */
    public function itReturnsItems(): void
    {
        $item1 = new SitemapItem('foo', new DateTimeImmutable('2021-01-01 19:00:00'));

        $collection = new SitemapItemCollection($item1);

        $item2 = new SitemapItem('bar', new DateTimeImmutable('2021-01-01 20:00:00'));
        $item3 = new SitemapItem('bar', new DateTimeImmutable('2021-01-01 18:00:00'));

        $collection->add($item2, $item3);

        $this->assertEquals(new DateTimeImmutable('2021-01-01 20:00:00'), $collection->lastPublicationDate());
        $this->assertEquals(3, count($collection->items()));
    }

    public function itReturnsNoItems(): void
    {
        $collection = new SitemapItemCollection();

        $this->assertEquals(null, $collection->lastPublicationDate());
        $this->assertEquals(0, count($collection->items()));
    }
}
