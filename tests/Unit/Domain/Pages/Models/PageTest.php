<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Pages\Models;

use App\Domain\Pages\Models\Page;
use Tests\TestCase;

/**
 * @covers \App\Domain\Pages\Models\Page
 */
class PageTest extends TestCase
{
    private function getPage(): Page
    {
        return new Page(
            'foo',
            'bar',
            'baz-baz',
            'Baz baz'
        );
    }

    /** @test */
    public function itConstructsANewPage(): void
    {
        $page = $this->getPage();

        $this->assertEquals('foo', $page->content());
        $this->assertEquals('bar', $page->description());
        $this->assertEquals('baz-baz', $page->slug());
        $this->assertEquals('Baz baz', $page->title());
    }

    /** @test */
    public function itReturnsAnArray(): void
    {
        $page = $this->getPage();

        $this->assertEquals([
            'content' => 'foo',
            'description' => 'bar',
            'slug' => 'baz-baz',
            'title' => 'Baz baz',
        ], $page->toArray());
    }
}
