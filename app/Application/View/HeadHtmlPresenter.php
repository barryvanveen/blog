<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
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

    public function __construct(
        AssetUrlBuilderInterface $assetUrlBuilder,
        ConfigurationInterface $configuration,
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->assetUrlBuilder = $assetUrlBuilder;
        $this->configuration = $configuration;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }

    public function present(): array
    {
        return [
            'base_url' => $this->configuration->string('app.url'),
            'csrf_token' => $this->session->token(),
            'css_path' => $this->assetUrlBuilder->get('app.css'),
            'about_url' => $this->urlGenerator->route('about', [], true),
        ];
    }
}
