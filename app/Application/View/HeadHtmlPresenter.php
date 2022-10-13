<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;

final class HeadHtmlPresenter implements PresenterInterface
{
    public function __construct(
        private AssetUrlBuilderInterface $assetUrlBuilder,
        private ConfigurationInterface $configuration,
        private SessionInterface $session,
        private UrlGeneratorInterface $urlGenerator,
        private RouterInterface $router,
    ) {
    }

    public function present(): array
    {
        return [
            'base_url' => $this->configuration->string('app.url'),
            'css_paths' => $this->getCssPaths(),
            'about_url' => $this->urlGenerator->route('about', [], true),
            'rss_url' => $this->urlGenerator->route('articles.rss', [], true),
        ];
    }

    private function getCssPaths(): array
    {
        $cssPaths = [
            $this->assetUrlBuilder->get('app.css'),
        ];

        if ($this->router->currentRouteIsAdminRoute()) {
            $cssPaths[] = $this->assetUrlBuilder->get('admin.css');
        }

        return $cssPaths;
    }
}
