<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Utils\MenuItem;

final class HeaderPresenter implements PresenterInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private RouterInterface $router,
    ) {
    }

    public function present(): array
    {
        if ($this->router->currentRouteIsAdminRoute()) {
            return [
                'home_url' => $this->urlGenerator->route('admin.dashboard'),
                'menu_items' => $this->getAdminMenuItems(),
            ];
        }

        return [
            'home_url' => $this->urlGenerator->route('home'),
            'menu_items' => $this->getMenuItems(),
        ];
    }

    /**
     * @return MenuItem[]
     */
    private function getAdminMenuItems(): array
    {
        return [
            new MenuItem('Articles', $this->urlGenerator->route('admin.articles.index')),
            new MenuItem('Pages', $this->urlGenerator->route('admin.pages.index')),
            new MenuItem('Comments', $this->urlGenerator->route('admin.comments.index')),
        ];
    }

    /**
     * @return MenuItem[]
     */
    private function getMenuItems(): array
    {
        return [
            new MenuItem('Articles', $this->urlGenerator->route('articles.index')),
            new MenuItem('About', $this->urlGenerator->route('about')),
        ];
    }
}
