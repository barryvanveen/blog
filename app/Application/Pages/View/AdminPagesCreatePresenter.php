<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;

final class AdminPagesCreatePresenter implements PresenterInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator, private SessionInterface $session)
    {
    }

    public function present(): array
    {
        return [
            'title' => 'New page',
            'url' => $this->urlGenerator->route('admin.pages.store'),
            'page' => $this->page(),
        ];
    }

    private function page(): array
    {
        return [
            'title' => $this->session->oldInput('title', ''),
            'slug' => $this->session->oldInput('slug', ''),
            'description' => $this->session->oldInput('description', ''),
            'content' => $this->session->oldInput('content', ''),
        ];
    }
}
