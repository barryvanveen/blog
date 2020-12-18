<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\AdminArticlesIndexPresenter;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Infrastructure\Adapters\LaravelCollection;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\ArticleMapper;
use Database\Factories\ArticleFactory;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\AdminArticlesIndexPresenter
 */
class AdminArticlesIndexPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var ArticleEloquentModel $article1 */
        $article1 = ArticleFactory::new()->make([
            'published_at' => '2020-01-17 17:03:05',
            'status' => (string) ArticleStatus::published(),
        ]);

        /** @var ArticleEloquentModel $article2 */
        $article2 = ArticleFactory::new()->make([
            'published_at' => '2020-02-18 17:03:05',
            'status' => (string) ArticleStatus::unpublished(),
        ]);

        $mapper = new ArticleMapper();

        $collection = $mapper->mapToDomainModels(
            new LaravelCollection([
                $article1->toArray(),
                $article2->toArray(),
            ])
        );

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::exact('admin.articles.create'))->willReturn('http://newurl');
        $urlGenerator->route(Argument::exact('admin.articles.edit'), Argument::type('array'))->willReturn('http://editurl');

        /** @var ObjectProphecy|ArticleRepositoryInterface $repository */
        $repository = $this->prophesize(ArticleRepositoryInterface::class);
        $repository->allOrdered()->willReturn($collection);

        $presenter = new AdminArticlesIndexPresenter(
            $repository->reveal(),
            $urlGenerator->reveal()
        );

        $this->assertEquals([
            'title' => 'Articles',
            'articles' => [
                [
                    'is_online' => true,
                    'uuid' => $article1->uuid,
                    'title' => $article1->title,
                    'published_at' => 'Jan 17, 2020',
                    'edit_url' => 'http://editurl',
                ],
                [
                    'is_online' => false,
                    'uuid' => $article2->uuid,
                    'title' => $article2->title,
                    'published_at' => 'Feb 18, 2020',
                    'edit_url' => 'http://editurl',
                ],
            ],
            'create_url' => 'http://newurl',
        ], $presenter->present());
    }
}
