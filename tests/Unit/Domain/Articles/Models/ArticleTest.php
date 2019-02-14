<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use Carbon\Carbon;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * @test
     *
     * @covers \App\Domain\Articles\Models\Article::setTitleAttribute
     */
    public function itSetsTheSlugWhenTheTitleIsSet()
    {
        $article = new Article();
        $article->title = 'Foo Article Title';

        $this->assertEquals('foo-article-title', $article->slug);
    }

    /**
     * @test
     *
     * @covers \App\Domain\Articles\Models\Article::create
     */
    public function itCreatesANewInstance()
    {
        $article = Article::create(
            3,
            'foo',
            'bar',
            Carbon::createFromTimeString('2019-02-14 20:11:00'),
            ArticleStatus::UNPUBLISHED(),
            'Baz baz'
        );

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals(3, $article->author_id);
        $this->assertEquals('foo', $article->content);
        $this->assertEquals('bar', $article->description);
        $this->assertTrue(Carbon::createFromTimeString('2019-02-14 20:11:00')->equalTo($article->published_at));
        $this->assertEquals('baz-baz', $article->slug);
        $this->assertEquals(ArticleStatus::UNPUBLISHED(), $article->status);
        $this->assertEquals('Baz baz', $article->title);
    }
}
