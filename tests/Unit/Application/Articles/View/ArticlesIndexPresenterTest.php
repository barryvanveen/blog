<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\ArticlesIndexPresenter;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\ArticlesIndexPresenter
 */
class ArticlesIndexPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $collection = new LaravelCollection();

        /** @var ArticleRepositoryInterface $repository */
        $repository = $this->prophesize(ArticleRepositoryInterface::class);
        $repository->allPublishedAndOrdered()->willReturn($collection);

        $presenter = new ArticlesIndexPresenter(
            $repository->reveal()
        );

        $this->assertEquals([
            'articles' => $collection,
        ], $presenter->present());
    }
}
