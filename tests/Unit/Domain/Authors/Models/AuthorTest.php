<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Authors\Models;

use App\Domain\Authors\Models\Author;
use Tests\TestCase;

/**
 * @covers \App\Domain\Authors\Models\Author
 */
class AuthorTest extends TestCase
{
    /** @test */
    public function itConstructsANewAuthor(): void
    {
        $author = new Author(
            'author-content',
            'author-description',
            'author-name',
            '123123'
        );

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('author-content', $author->content());
        $this->assertEquals('author-description', $author->description());
        $this->assertEquals('author-name', $author->name());
        $this->assertEquals('123123', $author->uuid());
    }

    /** @test */
    public function itConvertsToArray(): void
    {
        $author = new Author(
            'author-content',
            'author-description',
            'author-name',
            '123123'
        );

        $this->assertEquals([
            'content' => 'author-content',
            'description' => 'author-description',
            'name' => 'author-name',
            'uuid' => '123123',
        ], $author->toArray());
    }
}
