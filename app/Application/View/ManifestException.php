<?php

declare(strict_types=1);

namespace App\Application\View;

use RuntimeException;

class ManifestException extends RuntimeException
{
    public static function becauseManifestFileCouldNotBeRead(): self
    {
        return new self('Could not read or decode manifest file.');
    }

    public static function becauseManifestDoesNotContainPath(string $path)
    {
        return new self("Asset is missing from manifest file: {$path}.");
    }
}
