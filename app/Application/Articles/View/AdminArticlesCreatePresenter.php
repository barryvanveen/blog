<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\Enums\ArticleStatus;

final class AdminArticlesCreatePresenter implements PresenterInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    public function present(): array
    {
        return [
            'title' => 'New article',
            'create_article_url' => $this->urlGenerator->route('admin.articles.index'),
            'statuses' => $this->statuses(),
        ];
    }

    private function statuses(): array
    {
        return [
            [
                'value' => (string) ArticleStatus::unpublished(),
                'title' => 'Not published',
                'checked' => true,
            ],
            [
                'value' => (string) ArticleStatus::published(),
                'title' => 'Published',
                'checked' => false,
            ],
        ];
    }
}
