<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\PathBuilderInterface;
use Illuminate\Contracts\Foundation\Application;

class LaravelPathBuilder implements PathBuilderInterface
{
    /** @var Application */
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function publicPath(string $path): string
    {
        return $this->appendPath(
            $this->application->make('path.public'),
            $path
        );
    }

    public function assetPath(string $path): string
    {
        return $this->appendPath(
            $this->publicPath('dist'),
            $path
        );
    }

    private function appendPath(string $base, string $path): string
    {
        return $base.($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}
