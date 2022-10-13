<?php

declare(strict_types=1);

namespace App\Domain\Utils;

final class MenuItem
{
    public function __construct(private string $name, private string $url, private bool $openInNewWindow = false)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function openInNewWindow(): bool
    {
        return $this->openInNewWindow;
    }
}
