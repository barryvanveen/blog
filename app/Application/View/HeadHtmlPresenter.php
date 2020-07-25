<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;

final class HeadHtmlPresenter implements PresenterInterface
{
    /** @var AssetUrlBuilderInterface */
    private $assetUrlBuilder;

    /** @var ConfigurationInterface */
    private $configuration;

    /** @var SessionInterface */
    private $session;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var RouterInterface */
    private $router;

    public function __construct(
        AssetUrlBuilderInterface $assetUrlBuilder,
        ConfigurationInterface $configuration,
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator,
        RouterInterface $router
    ) {
        $this->assetUrlBuilder = $assetUrlBuilder;
        $this->configuration = $configuration;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
        $this->router = $router;
    }

    public function present(): array
    {
        return [
            'base_url' => $this->configuration->string('app.url'),
            'csrf_token' => $this->session->token(),
            'css_paths' => $this->getCssPaths(),
            'about_url' => $this->urlGenerator->route('about', [], true),
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
