<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * @covers \App\Domain\Articles\Models\Article
 */
class ArticleTest extends TestCase
{
    private function getArticle(
        DateTimeImmutable $dateTime,
        ArticleStatus $status
    ): Article {
        return new Article(
            '321321',
            'foo',
            'bar',
            $dateTime,
            'baz-baz',
            $status,
            'Baz baz',
            '123123'
        );
    }

    /** @test */
    public function itConstructsANewArticle(): void
    {
        $dateTime = new DateTimeImmutable('-1 day');

        $article = $this->getArticle(
            $dateTime,
            ArticleStatus::published()
        );

        $this->assertEquals('321321', $article->authorUuid());
        $this->assertEquals('foo', $article->content());
        $this->assertEquals('bar', $article->description());
        $this->assertEquals($dateTime->getTimestamp(), $article->publishedAt()->getTimestamp());
        $this->assertEquals('baz-baz', $article->slug());
        $this->assertEquals(true, $article->isOnline());
        $this->assertEquals('Baz baz', $article->title());
        $this->assertEquals('123123', $article->uuid());
    }

    /**
     * @test
     * @dataProvider isOnlineDataProvider
     *
     * @param DateTimeImmutable $dateTime
     * @param ArticleStatus $status
     * @param bool $expected
     */
    public function isOnline(DateTimeImmutable $dateTime, ArticleStatus $status, bool $expected): void
    {
        $article = $this->getArticle($dateTime, $status);

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

        $article = $this->getArticle(
            $dateTime,
            ArticleStatus::published()
        );

        $this->assertEquals([
            'author_uuid' => '321321',
            'content' => 'foo',
            'description' => 'bar',
            'published_at' => $dateTime,
            'slug' => 'baz-baz',
            'status' => ArticleStatus::published(),
            'title' => 'Baz baz',
            'uuid' => '123123',
        ], $article->toArray());
    }
}
