<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\ArticlesCreateCommentPresenter;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\ArticlesCreateCommentPresenter
 */
class ArticlesCreateCommentPresenterTest extends TestCase
{
    private const CREATE_COMMENT_URL = 'baaar';
    private const ARTICLE_UUID = 'fooooo';

    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var ArticleShowRequestInterface|ObjectProphecy $request */
        $request = $this->prophesize(ArticleShowRequestInterface::class);
        $request->uuid()->willReturn(self::ARTICLE_UUID);

        /** @var UrlGeneratorInterface|ObjectProphecy $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::any())->willReturn(self::CREATE_COMMENT_URL);

        $presenter = new ArticlesCreateCommentPresenter(
            $request->reveal(),
            $urlGenerator->reveal()
        );

        $result = $presenter->present();

        $this->assertEquals([
            'create_comment_url' => self::CREATE_COMMENT_URL,
            'article_uuid' => self::ARTICLE_UUID,
        ], $result);
    }
}
