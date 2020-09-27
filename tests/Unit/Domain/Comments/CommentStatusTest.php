<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Comments;

use App\Domain\Comments\CommentStatus;
use Tests\TestCase;

/**
 * @covers \App\Domain\Comments\CommentStatus
 */
class CommentStatusTest extends TestCase
{
    /** @test */
    public function itConstructsACommentStatus(): void
    {
        $unpublished = CommentStatus::unpublished();
        $published = CommentStatus::published();

        $this->assertEquals('0', (string) $unpublished);
        $this->assertEquals('1', (string) $published);

        $this->assertTrue($unpublished->equals(CommentStatus::unpublished()));
        $this->assertTrue($published->equals(CommentStatus::published()));
    }
}
