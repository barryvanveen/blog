<?php

declare(strict_types=1);

namespace App\Application\Util;

use Exception;

final class BaseViewModel
{
    public function asset(string $path): string
    {
        $manifestPath = public_path('dist/manifest.json');

        try {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        } catch (Exception $e) {
            throw new Exception('Could not read or decode manifest file.');
        }

        if (! isset($manifest[$path])) {
            throw new Exception("Asset is missing from manifest file: {$path}.");
        }

        return $manifest[$path];
    }
}
