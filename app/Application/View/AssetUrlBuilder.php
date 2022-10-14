<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\PathBuilderInterface;
use Exception;

class AssetUrlBuilder implements AssetUrlBuilderInterface
{
    public function __construct(
        private PathBuilderInterface $pathBuilder,
    ) {
    }

    public function get(string $path): string
    {
        $manifestPath = $this->pathBuilder->assetPath('manifest.json');

        try {
            $manifest = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception) {
            throw ManifestException::becauseManifestFileCouldNotBeRead();
        }

        if (! isset($manifest[$path])) {
            throw ManifestException::becauseManifestDoesNotContainPath($path);
        }

        return $manifest[$path];
    }
}
