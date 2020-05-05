<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Pages\Models;

use DateTimeImmutable;
use Tests\TestCase;

/**
 * @covers \App\Domain\Pages\Models\Page
 */
class PageTest extends TestCase
{
    /** @test */
    public function itConstructsANewPageAndReturnsAnArray(): void
    {
        $date = new DateTimeImmutable();

        $page = $this->getPage([
            'content' => 'foo',
            'description' => 'bar',
            'lastUpdated' => $date,
            'slug' => 'baz-baz',
            'title' => 'Baz baz',
        ]);

        $this->assertEquals('foo', $page->content());
        $this->assertEquals('bar', $page->description());
        $this->assertSame($date, $page->lastUpdated());
        $this->assertEquals('baz-baz', $page->slug());
        $this->assertEquals('Baz baz', $page->title());

        $this->assertEquals([
            'content' => 'foo',
            'description' => 'bar',
            'lastUpdated' => $date,
            'slug' => 'baz-baz',
            'title' => 'Baz baz',
        ], $page->toArray());
    }
}
