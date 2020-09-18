<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\RouterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;

final class JavascriptPresenter implements PresenterInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var AssetUrlBuilderInterface */
    private $assetUrlBuilder;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        RouterInterface $router,
        AssetUrlBuilderInterface $assetUrlBuilder,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->router = $router;
        $this->assetUrlBuilder = $assetUrlBuilder;
        $this->urlGenerator = $urlGenerator;
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
        $variables = [];

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
