<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * @covers \App\Domain\Articles\Models\Article
 */
class ArticleTest extends TestCase
{
    /** @test */
    public function itConstructsANewArticle(): void
    {
        $dateTime = new DateTimeImmutable('-1 day');

        $article = $this->getArticle([
            'published_at' => $dateTime,
            'status' => ArticleStatus::published(),
        ]);

        $this->assertEquals('foo', $article->content());
        $this->assertEquals('bar', $article->description());
        $this->assertEquals($dateTime->getTimestamp(), $article->publishedAt()->getTimestamp());
        $this->assertEquals('baz-baz', $article->slug());
        $this->assertEquals(true, $article->isOnline());
        $this->assertTrue($article->status()->equals(ArticleStatus::published()));
        $this->assertEquals('Baz baz', $article->title());
        $this->assertEquals('123123', $article->uuid());
    }

    /**
     * @test
     * @dataProvider isOnlineDataProvider
     */
    public function isOnline(DateTimeImmutable $dateTime, ArticleStatus $status, bool $expected): void
    {
        $article = $this->getArticle([
            'published_at' => $dateTime,
            'status' => $status,
        ]);

        $this->assertEquals($expected, $article->isOnline());
    }

    public function isOnlineDataProvider(): array
    {
        return [
            [new DateTimeImmutable('+1 day'), ArticleStatus::unpublished(), false],
            [new DateTimeImmutable('+1 day'), ArticleStatus::published(), false],
            [new DateTimeImmutable('-1 day'), ArticleStatus::unpublished(), false],
            [new DateTimeImmutable('-1 day'), ArticleStatus::published(), true],
        ];
    }

    /** @test */
    public function itReturnsAnArray(): void
    {
        $dateTime = new DateTimeImmutable();

        $article = $this->getArticle([
            'published_at' => $dateTime,
            'status' => ArticleStatus::published(),
            'updated_at' => $dateTime,
        ]);

        $this->assertEquals([
            'content' => 'foo',
            'description' => 'bar',
            'published_at' => $dateTime,
            'slug' => 'baz-baz',
            'status' => ArticleStatus::published(),
            'title' => 'Baz baz',
            'updated_at' => $dateTime,
            'uuid' => '123123',
        ], $article->toArray());
    }
}
