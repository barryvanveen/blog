<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\PathBuilderInterface;
use Exception;

class AssetUrlBuilder implements AssetUrlBuilderInterface
{
    /** @var PathBuilderInterface */
    private $pathBuilder;

    public function __construct(PathBuilderInterface $pathBuilder)
    {
        $this->pathBuilder = $pathBuilder;
    }

    public function get(string $path): string
    {
        $manifestPath = $this->pathBuilder->assetPath('manifest.json');

        try {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        } catch (Exception $e) {
            throw ManifestException::becauseManifestFileCouldNotBeRead();
        }

        if (! isset($manifest[$path])) {
            throw ManifestException::becauseManifestDoesNotContainPath($path);
        }

        return $manifest[$path];
    }
}
