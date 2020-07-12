<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\ArticlesItemPresenter;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use App\Domain\Utils\MetaData;
use DateTimeImmutable;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\ArticlesItemPresenter
 */
class ArticlesItemPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $uuid = 'myMockUuid';
        $title = 'titleString';
        $content = 'contentString';

        $article = new Article(
            $content,
            'descriptionString',
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-04 19:56:48'),
            'slug-string',
            ArticleStatus::published(),
            $title,
            $uuid
        );

        /** @var ObjectProphecy|ArticleRepositoryInterface $repository */
        $repository = $this->prophesize(ArticleRepositoryInterface::class);
        $repository->getPublishedByUuid(Argument::exact($uuid))->willReturn($article);

        /** @var ObjectProphecy|ArticleShowRequestInterface $request */
        $request = $this->prophesize(ArticleShowRequestInterface::class);
        $request->uuid()->willReturn($uuid);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://my-article-url');

        /** @var ObjectProphecy|DateTimeFormatterInterface $dateTimeFormatter */
        $dateTimeFormatter = $this->prophesize(DateTimeFormatterInterface::class);
        $dateTimeFormatter->metadata(Argument::any())->willReturn('metadata-string');
        $dateTimeFormatter->humanReadable(Argument::any())->willReturn('humanReadable-string');

        $presenter = new ArticlesItemPresenter(
            $repository->reveal(),
            $request->reveal(),
            $urlGenerator->reveal(),
            $dateTimeFormatter->reveal()
        );

        $result = $presenter->present();

        $this->assertEquals($title, $result['title']);
        $this->assertEquals('metadata-string', $result['publicationDateInAtomFormat']);
        $this->assertEquals('humanReadable-string', $result['publicationDateInHumanFormat']);
        $this->assertEquals($content, $result['content']);
        $this->assertInstanceOf(MetaData::class, $result['metaData']);
    }
}
