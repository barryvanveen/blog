<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments\View;

use App\Application\Comments\View\AdminCommentsCreatePresenter;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\View\AdminCommentsCreatePresenter
 */
class AdminCommentsCreatePresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'))->willReturn('http://myurl');

        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->oldInput('name', Argument::cetera())->willReturn('old name');
        $session->oldInput(Argument::cetera())->willReturn('');

        $article = $this->getArticle();
        $articles = new LaravelCollection([
            $article,
        ]);

        /** @var ObjectProphecy|ArticleRepositoryInterface $articleRepository */
        $articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $articleRepository->allOrdered()->willReturn($articles);

        $presenter = new AdminCommentsCreatePresenter(
            $urlGenerator->reveal(),
            $session->reveal(),
            $articleRepository->reveal()
        );

        $this->assertEquals([
            'title' => 'New comment',
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
                'article_uuid' => '',
                'content' => '',
                'created_at' => '',
                'email' => '',
                'ip' => '',
                'name' => 'old name',
                'status' => '',
            ],
        ], $presenter->present());
    }
}
