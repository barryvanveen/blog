<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments\Listeners;

use App\Application\Comments\Events\CommentWasCreated;
use App\Application\Comments\Events\CommentWasUpdated;
use App\Application\Comments\Listeners\CommentListener;
use App\Application\Core\EventInterface;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\MailerInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentRepositoryInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\Events\CommentWasCreated
 * @covers \App\Application\Comments\Events\CommentWasUpdated
 * @covers \App\Application\Comments\Listeners\CommentListener
 */
class CommentListenerTest extends TestCase
{
    private const COMMENT_UUID = 'asdasd';
    private const ARTICLE_UUID = 'qweqwe';
    private const ARTICLE_SLUG = 'zxczxc';

    private ObjectProphecy|CacheInterface $cache;

    private ObjectProphecy|UrlGeneratorInterface $urlGenerator;

    private ObjectProphecy|CommentRepositoryInterface $commentRepository;

    private ObjectProphecy|ArticleRepositoryInterface $articleRepository;

    private ObjectProphecy|MailerInterface $mailer;

    private CommentListener $listener;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->prophesize(CacheInterface::class);

        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->urlGenerator->route('articles.index')->willReturn('indexUrl');
        $this->urlGenerator->route('articles.show', ['uuid' => self::ARTICLE_UUID, 'slug' => self::ARTICLE_SLUG])->willReturn('articleUrl');

        $comment = $this->getComment([
            'uuid' => self::COMMENT_UUID,
            'article_uuid' => self::ARTICLE_UUID,
        ]);

        $article = $this->getArticle([
            'uuid' => self::ARTICLE_UUID,
            'slug' => self::ARTICLE_SLUG,
        ]);

        $this->commentRepository = $this->prophesize(CommentRepositoryInterface::class);
        $this->commentRepository->getByUuid(self::COMMENT_UUID)->willReturn($comment);

        $this->articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $this->articleRepository->getByUuid(self::ARTICLE_UUID)->willReturn($article);

        $this->mailer = $this->prophesize(MailerInterface::class);

        $this->listener = new CommentListener(
            $this->cache->reveal(),
            $this->urlGenerator->reveal(),
            $this->commentRepository->reveal(),
            $this->articleRepository->reveal(),
            $this->mailer->reveal()
        );
    }

    /**
     * @test
     * @dataProvider eventDataProvider
     */
    public function itClearsCachesWhenAnArticleWasCreatedOrUpdated(EventInterface $event): void
    {
        $this->cache->forget('indexUrl')
            ->shouldBeCalled();

        $this->cache->forget('articleUrl')
            ->shouldBeCalled();

        $this->listener->handle($event);
    }

    public function eventDataProvider(): array
    {
        return [
            [
                'event' => new CommentWasCreated(self::COMMENT_UUID),
            ],
            [
                'event' => new CommentWasUpdated(self::COMMENT_UUID),
            ],
        ];
    }

    /** @test */
    public function itSendsAnEmailWhenCommentWasCreated(): void
    {
        $event = new CommentWasCreated(self::COMMENT_UUID);

        $this->mailer->sendNewCommentEmail(Argument::type(Comment::class))
            ->shouldBeCalled();

        $this->listener->handle($event);
    }
}
