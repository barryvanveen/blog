<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use DateTime;
use League\CommonMark\ConverterInterface;

final class ArticlesItemPresenter implements PresenterInterface
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var ArticleShowRequestInterface */
    private $request;

    /** @var ConverterInterface */
    private $markdownConverter;

    public function __construct(
        ArticleRepositoryInterface $repository,
        ArticleShowRequestInterface $request,
        ConverterInterface $markdownConverter
    ) {
        $this->repository = $repository;
        $this->request = $request;
        $this->markdownConverter = $markdownConverter;
    }

    public function present(): array
    {
        $article = $this->repository->getByUuid($this->request->uuid());

        return [
            'title' => $article->title(),
            'publicationDateInAtomFormat' => $this->publicationDateInAtomFormat($article),
            'publicationDateInHumanFormat' => $this->publicationDateInHumanFormat($article),
            'content' => $this->htmlContent($article),
        ];
    }

    private function publicationDateInAtomFormat(Article $article): string
    {
        return $article->publishedAt()->format(DateTime::ATOM);
    }

    private function publicationDateInHumanFormat(Article $article): string
    {
        return $article->publishedAt()->format('M d, Y');
    }

    private function htmlContent(Article $article): string
    {
        return $this->markdownConverter->convertToHtml(
            $article->content()
        );
    }
}
