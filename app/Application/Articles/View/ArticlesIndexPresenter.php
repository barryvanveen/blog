<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Utils\MetaData;

final class ArticlesIndexPresenter implements PresenterInterface
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var MarkdownConverterInterface */
    private $markdownConverter;

    public function __construct(
        ArticleRepositoryInterface $repository,
        UrlGeneratorInterface $urlGenerator,
        MarkdownConverterInterface $markdownConverter
    ) {
        $this->repository = $repository;
        $this->urlGenerator = $urlGenerator;
        $this->markdownConverter = $markdownConverter;
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
            $presentableArticles[] = [
                'title' => $article->title(),
                'description' => $this->markdownConverter->convertToHtml($article->description()),
                'url' => $this->urlGenerator->route('articles.show', ['uuid' => $article->uuid(), 'slug' => $article->slug()]),
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
