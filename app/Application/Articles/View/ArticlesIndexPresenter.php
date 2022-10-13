<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Utils\MetaData;

final class ArticlesIndexPresenter implements PresenterInterface
{
    public function __construct(
        private ArticleRepositoryInterface $repository,
        private UrlGeneratorInterface $urlGenerator,
        private MarkdownConverterInterface $markdownConverter,
        private DateTimeFormatterInterface $dateTimeFormatter,
        private CommentRepositoryInterface $commentRepository,
    ) {
    }

    public function present(): array
    {
        return [
            'articles' => $this->articles(),
            'metaData' => $this->buildMetaData(),
        ];
    }

    private function articles(): array
    {
        /** @var Article[] $articles */
        $articles = $this->repository->allPublishedAndOrdered();

        $presentableArticles = [];

        foreach ($articles as $article) {
            $comments = $this->commentRepository->onlineOrderedByArticleUuid($article->uuid());

            $presentableArticles[] = [
                'title' => $article->title(),
                'description' => $this->markdownConverter->convertToHtml($article->description()),
                'url' => $this->urlGenerator->route('articles.show', ['uuid' => $article->uuid(), 'slug' => $article->slug()]),
                'publication_date' => $this->dateTimeFormatter->humanReadable($article->publishedAt()),
                'publication_date_meta' => $this->dateTimeFormatter->metadata($article->publishedAt()),
                'comments' => $comments->count(),
            ];
        }

        return $presentableArticles;
    }

    private function buildMetaData(): MetaData
    {
        return new MetaData(
            'Articles',
            'Overview of all blog posts. Topics include Laravel Framework, web development, tips and tricks.',
            $this->urlGenerator->route('articles.index'),
            MetaData::TYPE_WEBSITE
        );
    }
}
