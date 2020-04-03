<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\AdminArticlesEditPresenter;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Requests\AdminArticleEditRequestInterface;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\ArticleMapper;
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
        $article = factory(ArticleEloquentModel::class)->make([
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
            $mapper->mapToDomainModel($article)
        );

        /** @var ObjectProphecy|AdminArticleEditRequestInterface $request */
        $request = $this->prophesize(AdminArticleEditRequestInterface::class);
        $request->uuid()->willReturn($article->uuid);

        $presenter = new AdminArticlesEditPresenter(
            $repository->reveal(),
            $urlGenerator->reveal(),
            $request->reveal()
        );

        $this->assertEquals([
            'title' => 'Edit article',
            'update_article_url' => 'http://myurl',
            'statuses' => [
                '0' => [
                    'value' => '0',
                    'title' => 'Not published',
                    'checked' => false,
                ],
                '1' => [
                    'value' => '1',
                    'title' => 'Published',
                    'checked' => true,
                ],
            ],
            'article' => [
                'title' => $article->title,
                'published_at' => $article->published_at,
                'description' => $article->description,
                'content' => $article->content,
                'status' => true,
            ],
        ], $presenter->present());
    }
}
