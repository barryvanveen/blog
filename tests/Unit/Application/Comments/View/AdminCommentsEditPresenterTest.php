<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments\View;

use App\Application\Comments\View\AdminCommentsEditPresenter;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Comments\Requests\AdminCommentEditRequestInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\View\AdminCommentsEditPresenter
 */
class AdminCommentsEditPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $comment = $this->getComment();

        /** @var ObjectProphecy|CommentRepositoryInterface $commentRepository */
        $commentRepository = $this->prophesize(CommentRepositoryInterface::class);
        $commentRepository->getByUuid($comment->uuid())->willReturn($comment);

        $article = $this->getArticle();
        $articles = new LaravelCollection([
            $article,
        ]);

        /** @var ObjectProphecy|ArticleRepositoryInterface $articleRepository */
        $articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $articleRepository->allOrdered()->willReturn($articles);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'), Argument::type('array'))->willReturn('http://myurl');

        /** @var AdminCommentEditRequestInterface $request */
        $request = $this->prophesize(AdminCommentEditRequestInterface::class);
        $request->uuid()->willReturn($comment->uuid());

        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->oldInput('name', Argument::cetera())->willReturn('old name');
        $session->oldInput(Argument::cetera())->willReturn(null);

        $presenter = new AdminCommentsEditPresenter(
            $commentRepository->reveal(),
            $urlGenerator->reveal(),
            $request->reveal(),
            $session->reveal(),
            $articleRepository->reveal()
        );

        $this->assertEquals([
            'title' => 'Edit comment',
            'url' => 'http://myurl',
            'statuses' => [
                '0' => [
                    'value' => '0',
                    'title' => 'Offline',
                ],
                '1' => [
                    'value' => '1',
                    'title' => 'Online',
                ],
            ],
            'articles' => [
                [
                    'value' => '',
                    'name' => '__Comment on article__',
                ],
                [
                    'value' => $article->uuid(),
                    'name' => $article->title(),
                ],
            ],
            'comment' => [
                'article_uuid' => $comment->articleUuid(),
                'content' => $comment->content(),
                'created_at' => $comment->createdAt()->format('Y-m-d H:i:s'),
                'email' => $comment->email(),
                'ip' => $comment->ip(),
                'name' => 'old name',
                'status' => (string) $comment->status(),
            ],
        ], $presenter->present());
    }
}
