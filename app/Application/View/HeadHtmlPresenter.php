<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\SessionInterface;

final class HeadHtmlPresenter implements PresenterInterface
{
    /** @var AssetUrlBuilderInterface */
    private $assetUrlBuilder;

    /** @var ConfigurationInterface */
    private $configuration;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        AssetUrlBuilderInterface $assetUrlBuilder,
        ConfigurationInterface $configuration,
        SessionInterface $session
    ) {
        $this->assetUrlBuilder = $assetUrlBuilder;
        $this->configuration = $configuration;
        $this->session = $session;
    }

    public function present(): array
    {
        return [
            'base_url' => $this->configuration->string('app.url'),
            'csrf_token' => $this->session->token(),
            'css_path' => $this->assetUrlBuilder->get('app.css'),
        ];
    }
}
