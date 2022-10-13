<?php

declare(strict_types=1);

namespace App\Application\View\Admin;

use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;

final class DashboardPresenter implements PresenterInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private GuardInterface $guard,
        private ArticleRepositoryInterface $articleRepository,
        private PageRepositoryInterface $pageRepository,
        private DateTimeFormatterInterface $dateTimeFormatter,
    ) {
    }

    public function present(): array
    {
        return [
            'name' => $this->getUserName(),
            'stats' => [
                'articles' => $this->getArticleStats(),
                'pages' => $this->getPageStats(),
            ],
            'clearCacheUrl' => $this->urlGenerator->route('admin.clear-cache'),
            'logoutUrl' => $this->urlGenerator->route('logout.post'),
            'metaData' => $this->buildMetaData(),
        ];
    }

    private function buildMetaData(): MetaData
    {
        return new MetaData(
            'Admin',
            'Admin section',
            $this->urlGenerator->route('admin.dashboard'),
            MetaData::TYPE_WEBSITE
        );
    }

    private function getUserName(): string
    {
        return $this->guard->user()->name();
    }

    private function getArticleStats(): array
    {
        $articles = $this->articleRepository->allPublishedAndOrdered();

        return [
            'total' => $articles->count(),
            'lastUpdate' => $this->getLastUpdateText(
                $articles->map(fn (Article $article) => $article->updatedAt())
            ),
        ];
    }

    private function getPageStats(): array
    {
        $pages = $this->pageRepository->allOrdered();

        return [
            'total' => $pages->count(),
            'lastUpdate' => $this->getLastUpdateText(
                $pages->map(fn (Page $page) => $page->lastUpdated())
            ),
        ];
    }

    private function getLastUpdateText(array $dates): string
    {
        if (empty($dates)) {
            return 'No data';
        }

        return $this->dateTimeFormatter->humanReadable(max($dates));
    }
}
