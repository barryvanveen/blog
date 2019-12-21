<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Menu\MenuItem;

final class FooterPresenter implements PresenterInterface
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
            new MenuItem('© Barry van Veen', $this->urlGenerator->route('home')),
            new MenuItem('LinkedIn', 'https://www.linkedin.com/in/barryvanveen/', true),
            new MenuItem('GitHub', 'https://github.com/barryvanveen/', true),
            new MenuItem('RSS', $this->urlGenerator->route('rss')),
        ];
    }
}
