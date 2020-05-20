<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;

final class AdminPagesCreatePresenter implements PresenterInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        SessionInterface $session
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

    public function present(): array
    {
        return [
            'title' => 'New page',
            'create_url' => $this->urlGenerator->route('admin.pages.store'),
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
