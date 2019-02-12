<?php

namespace Tests\Unit\Domain\Articles\Models;

use App\Domain\Articles\Models\Article;
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
}
