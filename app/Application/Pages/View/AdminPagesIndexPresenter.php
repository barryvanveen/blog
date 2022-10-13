<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;

final class AdminPagesIndexPresenter implements PresenterInterface
{
    public function __construct(private PageRepositoryInterface $repository, private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function present(): array
    {
        return [
            'title' => 'Pages',
            'pages' => $this->pages(),
            'create_url' => $this->urlGenerator->route('admin.pages.create'),
            'metaData' => $this->buildMetaData(),
        ];
    }
    private function pages(): array
    {
        /** @var Page[] $pages */
        $pages = $this->repository->allOrdered();

        $presentablePages = [];

        foreach ($pages as $page) {
            $presentablePages[] = [
                'slug' => $page->slug(),
                'title' => $page->title(),
                'edit_url' => $this->urlGenerator->route('admin.pages.edit', ['slug' => $page->slug()]),
            ];
        }

        return $presentablePages;
    }

    private function buildMetaData(): MetaData
    {
        return new MetaData(
            'Pages',
            'Admin section for pages',
            $this->urlGenerator->route('admin.pages.index'),
            MetaData::TYPE_WEBSITE
        );
    }
}
