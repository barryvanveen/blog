<?php

declare(strict_types=1);

namespace App\Infrastructure\View;

use Exception;

class PresenterComposerException extends Exception
{
    public static function becauseFileCouldNotBeFound(string $viewPath): self
    {
        return new self('File could not be found: ' . $viewPath);
    }

    public static function becausePresenterDoesNotImplementInterface(string $class): self
    {
        return new self('Presenter should implement correct interface: ' . $class);
    }
}
