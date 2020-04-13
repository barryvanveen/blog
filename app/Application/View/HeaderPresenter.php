<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Utils\MenuItem;

final class HeaderPresenter implements PresenterInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var RouterInterface */
    private $router;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        RouterInterface $router
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->router = $router;
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
     * @return \App\Domain\Utils\MenuItem[]
     */
    private function getAdminMenuItems(): array
    {
        return [
            new MenuItem('Articles', $this->urlGenerator->route('admin.articles.index')),
            new MenuItem('Elements', $this->urlGenerator->route('admin.elements')),
            new MenuItem('Logout', $this->urlGenerator->route('admin.dashboard')),
        ];
    }

    /**
     * @return \App\Domain\Utils\MenuItem[]
     */
    private function getMenuItems(): array
    {
        return [
            new MenuItem('Articles', $this->urlGenerator->route('articles.index')),
            new MenuItem('About', $this->urlGenerator->route('about')),
        ];
    }
}
