<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;

final class JavascriptPresenter implements PresenterInterface
{
    private RouterInterface $router;
    private AssetUrlBuilderInterface $assetUrlBuilder;
    private UrlGeneratorInterface $urlGenerator;
    private ConfigurationInterface $configuration;

    public function __construct(
        RouterInterface $router,
        AssetUrlBuilderInterface $assetUrlBuilder,
        UrlGeneratorInterface $urlGenerator,
        ConfigurationInterface $configuration,
    ) {
        $this->router = $router;
        $this->assetUrlBuilder = $assetUrlBuilder;
        $this->urlGenerator = $urlGenerator;
        $this->configuration = $configuration;
    }

    public function present(): array
    {
        return [
            'js_variables' => $this->getJavascriptVariables(),
            'js_paths' => $this->getJavascriptPaths(),
        ];
    }

    private function getJavascriptVariables(): array
    {
        $variables = [
            'base_url' => $this->configuration->string('app.url'),
        ];

        if ($this->router->currentRouteIsAdminRoute()) {
            $variables['markdown_to_html_url'] = $this->urlGenerator->route('admin.markdown-to-html');
        }

        return $variables;
    }

    private function getJavascriptPaths(): array
    {
        $javascriptPaths = [
            $this->assetUrlBuilder->get('app.js'),
        ];

        if ($this->router->currentRouteIsAdminRoute()) {
            $javascriptPaths[] = $this->assetUrlBuilder->get('admin.js');
        }

        return $javascriptPaths;
    }
}
