<?php

declare(strict_types=1);

namespace App\Domain\Utils;

use DateTimeInterface;

final class SitemapItem
{
    public function __construct(private string $url, private DateTimeInterface $lastModificationDate)
    {
    }

    public function url(): string
    {
        return $this->url;
    }

    public function lastModificationDate(): DateTimeInterface
    {
        return $this->lastModificationDate;
    }
}
