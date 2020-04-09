<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Utils\MetaData;

final class ArticlesIndexPresenter implements PresenterInterface
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        ArticleRepositoryInterface $repository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->repository = $repository;
        $this->urlGenerator = $urlGenerator;
    }

    public function present(): array
    {
        return [
            'articles' => $this->repository->allPublishedAndOrdered(),
            'metaData' => $this->buildMetaData(),
        ];
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
