<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\SessionInterface;

final class BaseViewPresenter implements PresenterInterface
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
            'js_path' => $this->assetUrlBuilder->get('app.js'),
            'locale' => $this->buildLocale(),
        ];
    }

    private function buildLocale(): string
    {
        return str_replace(
            '_',
            '-',
            $this->configuration->string('app.locale')
        );
    }
}
