<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Articles\View;

use App\Application\Articles\View\ArticlesCommentsPresenter;
use App\Application\Interfaces\ConfigurationInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Comments\CommentStatus;
use App\Infrastructure\Adapters\LaravelCollection;
use DateTimeImmutable;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Articles\View\ArticlesCommentsPresenter
 */
class ArticlesCommentsPresenterTest extends TestCase
{
    private const ARTICLE_UUID = 'fooooo';

    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        $comments = new LaravelCollection([
            $this->makeComment(1),
            $this->makeComment(2),
            $this->makeComment(3),
        ]);

        /** @var ArticleShowRequestInterface|ObjectProphecy $request */
        $request = $this->prophesize(ArticleShowRequestInterface::class);
        $request->uuid()->willReturn(self::ARTICLE_UUID);

        /** @var DateTimeFormatterInterface|ObjectProphecy $dateTimeFormatter */
        $dateTimeFormatter = $this->prophesize(DateTimeFormatterInterface::class);
        $dateTimeFormatter->humanReadable(Argument::any())->willReturn('readableDate');
        $dateTimeFormatter->metadata(Argument::any())->willReturn('metaDate');

        /** @var CommentRepositoryInterface|ObjectProphecy $commentRepository */
        $commentRepository = $this->prophesize(CommentRepositoryInterface::class);
        $commentRepository->onlineOrderedByArticleUuid(self::ARTICLE_UUID)->willReturn($comments);

        /** @var ConfigurationInterface|ObjectProphecy $configuration */
        $configuration = $this->prophesize(ConfigurationInterface::class);
        $configuration->boolean('comments.enabled')->willReturn(true);

        $presenter = new ArticlesCommentsPresenter(
            $request->reveal(),
            $dateTimeFormatter->reveal(),
            $commentRepository->reveal(),
            $configuration->reveal()
        );

        $result = $presenter->present();

        $this->assertEquals([
            'total' => 3,
            'comments' => [
                [
                    'name' => 'Name1',
                    'date_human_readable' => 'readableDate',
                    'date_meta' => 'metaDate',
                    'content' => 'Content1',
                    'uuid' => 'uuid1',
                ],
                [
                    'name' => 'Name2',
                    'date_human_readable' => 'readableDate',
                    'date_meta' => 'metaDate',
                    'content' => 'Content2',
                    'uuid' => 'uuid2',
                ],
                [
                    'name' => 'Name3',
                    'date_human_readable' => 'readableDate',
                    'date_meta' => 'metaDate',
                    'content' => 'Content3',
                    'uuid' => 'uuid3',
                ],
            ],
            'comments_enabled' => true,
        ], $result);
    }

    private function makeComment(int $comment): Comment
    {
        return new Comment(
            self::ARTICLE_UUID,
            'Content'.$comment,
            new DateTimeImmutable('-1 day'),
            'foo@bar.baz',
            '123.123.123.123',
            'Name'.$comment,
            CommentStatus::published(),
            'uuid'.$comment
        );
    }
}
