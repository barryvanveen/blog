<?php

declare(strict_types=1);

namespace App\Domain\Utils;

final class MenuItem
{
    /** @var string */
    private $name;

    /** @var string */
    private $url;

    /** @var bool */
    private $openInNewWindow;

    public function __construct(string $name, string $url, bool $openInNewWindow = false)
    {
        $this->name = $name;
        $this->url = $url;
        $this->openInNewWindow = $openInNewWindow;
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
