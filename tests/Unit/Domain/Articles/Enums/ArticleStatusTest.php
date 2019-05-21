<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use Tests\TestCase;

/**
 * @covers \App\Domain\Articles\Enums\ArticleStatus
 */
class ArticleStatusTest extends TestCase
{
    /** @test */
    public function itConstructsAnArticleStatus(): void
    {
        $unpublished = ArticleStatus::unpublished();
        $published = ArticleStatus::published();

        $this->assertEquals('0', (string) $unpublished);
        $this->assertEquals('1', (string) $published);

        $this->assertTrue($unpublished->equals(ArticleStatus::unpublished()));
        $this->assertTrue($published->equals(ArticleStatus::published()));
    }
}
