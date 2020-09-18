<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use App\Domain\Utils\MetaData;

final class ArticlesItemPresenter implements PresenterInterface
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var ArticleShowRequestInterface */
    private $request;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var DateTimeFormatterInterface */
    private $dateTimeFormatter;

    /** @var MarkdownConverterInterface */
    private $markdownConverter;

    public function __construct(
        ArticleRepositoryInterface $repository,
        ArticleShowRequestInterface $request,
        UrlGeneratorInterface $urlGenerator,
        DateTimeFormatterInterface $dateTimeFormatter,
        MarkdownConverterInterface $markdownConverter
    ) {
        $this->repository = $repository;
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
        $this->dateTimeFormatter = $dateTimeFormatter;
        $this->markdownConverter = $markdownConverter;
    }

    public function present(): array
    {
        $article = $this->repository->getPublishedByUuid($this->request->uuid());

        return [
            'title' => $article->title(),
            'publicationDateInAtomFormat' => $this->publicationDateInAtomFormat($article),
            'publicationDateInHumanFormat' => $this->publicationDateInHumanFormat($article),
            'content' => $this->markdownConverter->convertToHtml($article->content()),
            'metaData' => $this->buildMetaData($article),
        ];
    }

    private function publicationDateInAtomFormat(Article $article): string
    {
        return $this->dateTimeFormatter->metadata($article->publishedAt());
    }

    private function publicationDateInHumanFormat(Article $article): string
    {
        return $this->dateTimeFormatter->humanReadable($article->publishedAt());
    }

    private function buildMetaData(Article $article): MetaData
    {
        return new MetaData(
            $article->title(),
            $article->description(),
            $this->urlGenerator->route('articles.show', [
                'uuid' => $article->uuid(),
                'slug' => $article->slug(),
            ], true),
            MetaData::TYPE_ARTICLE
        );
    }
}
