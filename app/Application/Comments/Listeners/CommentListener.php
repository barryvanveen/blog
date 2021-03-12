<?php

declare(strict_types=1);

namespace App\Application\Comments\Listeners;

use App\Application\Comments\Events\CommentWasCreated;
use App\Application\Comments\Events\CommentWasUpdated;
use App\Application\Core\BaseEventListener;
use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\MailerInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Comments\CommentRepositoryInterface;

final class CommentListener extends BaseEventListener
{
    private CacheInterface $cache;
    private UrlGeneratorInterface $urlGenerator;
    private CommentRepositoryInterface $commentRepository;
    private ArticleRepositoryInterface $articleRepository;
    private MailerInterface $mailer;

    public function __construct(
        CacheInterface $cache,
        UrlGeneratorInterface $urlGenerator,
        CommentRepositoryInterface $commentRepository,
        ArticleRepositoryInterface $articleRepository,
        MailerInterface $mailer
    ) {
        $this->cache = $cache;
        $this->urlGenerator = $urlGenerator;
        $this->commentRepository = $commentRepository;
        $this->articleRepository = $articleRepository;
        $this->mailer = $mailer;
    }

    public function handleCommentWasCreated(CommentWasCreated $event): void
    {
        $this->clearArticleCache($event->uuid());

        $this->clearArticleIndexCache();

        $this->sendNewCommentEmail($event->uuid());
    }

    public function handleCommentWasUpdated(CommentWasUpdated $event): void
    {
        $this->clearArticleCache($event->uuid());

        $this->clearArticleIndexCache();
    }

    private function clearArticleCache(string $commentUuid): void
    {
        $article = $this->getArticleByComment($commentUuid);

        $articleUrl = $this->urlGenerator->route('articles.show', [
            'uuid' => $article->uuid(),
            'slug' => $article->slug(),
        ]);
        $this->cache->forget($articleUrl);
    }

    private function clearArticleIndexCache(): void
    {
        $indexUrl = $this->urlGenerator->route('articles.index');
        $this->cache->forget($indexUrl);
    }

    private function getArticleByComment(string $commentUuid): Article
    {
        $comment = $this->commentRepository->getByUuid($commentUuid);

        return $this->articleRepository->getByUuid($comment->articleUuid());
    }

    private function sendNewCommentEmail(string $commentUuid): void
    {
        $comment = $this->commentRepository->getByUuid($commentUuid);

        $this->mailer->sendNewCommentEmail($comment);
    }
}
