<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Menu\MenuItem;

final class HeaderPresenter implements PresenterInterface
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
            'home_url' => $this->urlGenerator->route('home'),
            'menu_items' => $this->getMenuItems(),
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
