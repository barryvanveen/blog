<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Pages\Requests\AdminPageEditRequestInterface;

final class AdminPagesEditPresenter implements PresenterInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var SessionInterface */
    private $session;

    /** @var PageRepositoryInterface */
    private $repository;

    /** @var AdminPageEditRequestInterface */
    private $request;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        SessionInterface $session,
        PageRepositoryInterface $pageRepository,
        AdminPageEditRequestInterface $request
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
        $this->repository = $pageRepository;
        $this->request = $request;
    }

    public function present(): array
    {
        /** @var Page $page */
        $page = $this->repository->getBySlug($this->request->slug());

        return [
            'title' => 'Edit page',
            'url' => $this->urlGenerator->route('admin.pages.update', ['slug' => $page->slug()]),
            'page' => $this->page($page),
        ];
    }

    private function page(Page $page): array
    {
        return [
            'title' => $this->session->oldInput('title') ?? $page->title(),
            'slug' => $this->session->oldInput('slug') ?? $page->slug(),
            'description' => $this->session->oldInput('description') ?? $page->description(),
            'content' => $this->session->oldInput('content') ?? $page->content(),
        ];
    }
}
