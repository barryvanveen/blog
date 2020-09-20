<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\AdminArticlesEditPresenter;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Requests\AdminArticleEditRequestInterface;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\ArticleMapper;
use Database\Factories\ArticleFactory;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\AdminArticlesEditPresenter
 */
class AdminArticlesEditPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = ArticleFactory::new()->make([
            'published_at' => '2020-01-23 20:30:26',
            'status' => (string) ArticleStatus::published(),
        ]);

        $mapper = new ArticleMapper();

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'), Argument::type('array'))->willReturn('http://myurl');

        /** @var ObjectProphecy|ArticleRepositoryInterface $repository */
        $repository = $this->prophesize(ArticleRepositoryInterface::class);
        $repository->getByUuid(Argument::exact($article->uuid))->willReturn(
            $mapper->mapToDomainModel($article->toArray())
        );

        /** @var ObjectProphecy|AdminArticleEditRequestInterface $request */
        $request = $this->prophesize(AdminArticleEditRequestInterface::class);
        $request->uuid()->willReturn($article->uuid);

        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->oldInput('title', Argument::cetera())->willReturn('old title');
        $session->oldInput(Argument::cetera())->willReturnArgument(1);

        $presenter = new AdminArticlesEditPresenter(
            $repository->reveal(),
            $urlGenerator->reveal(),
            $request->reveal(),
            $session->reveal()
        );

        $this->assertEquals([
            'title' => 'Edit article',
            'update_article_url' => 'http://myurl',
            'statuses' => [
                '0' => [
                    'value' => '0',
                    'title' => 'Not published',
                ],
                '1' => [
                    'value' => '1',
                    'title' => 'Published',
                ],
            ],
            'article' => [
                'title' => 'old title',
                'published_at' => $article->published_at,
                'description' => $article->description,
                'content' => $article->content,
                'status' => '1',
            ],
        ], $presenter->present());
    }
}
