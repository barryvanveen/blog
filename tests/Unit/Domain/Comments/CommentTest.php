<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Comments;

use App\Domain\Comments\CommentStatus;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * @covers \App\Domain\Comments\Comment
 */
class CommentTest extends TestCase
{
    /** @test */
    public function itConstructsANewComment(): void
    {
        $dateTime = new DateTimeImmutable('-1 day');

        $comment = $this->getComment([
            'created_at' => $dateTime,
            'status' => CommentStatus::published(),
        ]);

        $this->assertEquals('987987', $comment->articleUuid());
        $this->assertEquals('foo', $comment->content());
        $this->assertEquals($dateTime->getTimestamp(), $comment->createdAt()->getTimestamp());
        $this->assertEquals('bar@baz.tld', $comment->email());
        $this->assertEquals('123.123.123.123', $comment->ip());
        $this->assertEquals('Foo Bar', $comment->name());
        $this->assertEquals(true, $comment->isOnline());
        $this->assertTrue($comment->status()->equals(CommentStatus::published()));
        $this->assertEquals('123123', $comment->uuid());
    }

    /**
     * @test
     * @dataProvider isOnlineDataProvider
     *
     * @param CommentStatus $status
     * @param bool $expected
     */
    public function isOnline(CommentStatus $status, bool $expected): void
    {
        $comment = $this->getComment([
            'status' => $status,
        ]);

        $this->assertEquals($expected, $comment->isOnline());
    }

    public function isOnlineDataProvider(): array
    {
        return [
            [CommentStatus::unpublished(), false],
            [CommentStatus::published(), true],
        ];
    }

    /** @test */
    public function itReturnsAnArray(): void
    {
        $dateTime = new DateTimeImmutable();

        $comment = $this->getComment([
            'created_at' => $dateTime,
            'status' => CommentStatus::published(),
        ]);

        $this->assertEquals([
            'article_uuid' => '987987',
            'content' => 'foo',
            'created_at' => $dateTime,
            'email' => 'bar@baz.tld',
            'ip' => '123.123.123.123',
            'name' => 'Foo Bar',
            'status' => CommentStatus::published(),
            'uuid' => '123123',
        ], $comment->toArray());
    }
}
