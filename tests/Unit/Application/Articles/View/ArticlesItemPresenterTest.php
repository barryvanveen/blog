<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\ArticlesItemPresenter;
use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
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
        $html = 'htmlContentString';

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
        $repository->getByUuid(Argument::exact($uuid))->willReturn($article);

        /** @var ObjectProphecy|ArticleShowRequestInterface $request */
        $request = $this->prophesize(ArticleShowRequestInterface::class);
        $request->uuid()->willReturn($uuid);

        /** @var ObjectProphecy|MarkdownConverterInterface $converter */
        $converter = $this->prophesize(MarkdownConverterInterface::class);
        $converter->convertToHtml(Argument::exact($content))->willReturn($html);

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://my-article-url');

        $presenter = new ArticlesItemPresenter(
            $repository->reveal(),
            $request->reveal(),
            $converter->reveal(),
            $urlGenerator->reveal()
        );

        $result = $presenter->present();

        $this->assertEquals($title, $result['title']);
        $this->assertEquals('2020-01-04T19:56:48+01:00', $result['publicationDateInAtomFormat']);
        $this->assertEquals('Jan 04, 2020', $result['publicationDateInHumanFormat']);
        $this->assertEquals($html, $result['content']);
        $this->assertInstanceOf(MetaData::class, $result['metaData']);
    }
}
