<?php

declare(strict_types=1);

namespace App\Application\View;

final class JavascriptPresenter implements PresenterInterface
{
    /** @var AssetUrlBuilderInterface */
    private $assetUrlBuilder;

    public function __construct(
        AssetUrlBuilderInterface $assetUrlBuilder
    ) {
        $this->assetUrlBuilder = $assetUrlBuilder;
    }

    public function present(): array
    {
        return [
            'js_path' => $this->assetUrlBuilder->get('app.js'),
        ];
    }
}
