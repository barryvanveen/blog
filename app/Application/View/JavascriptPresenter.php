<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\RouterInterface;

final class JavascriptPresenter implements PresenterInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var AssetUrlBuilderInterface */
    private $assetUrlBuilder;

    public function __construct(
        RouterInterface $router,
        AssetUrlBuilderInterface $assetUrlBuilder
    ) {
        $this->router = $router;
        $this->assetUrlBuilder = $assetUrlBuilder;
    }

    public function present(): array
    {
        return [
            'js_paths' => $this->getJavascriptPaths(),
        ];
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
