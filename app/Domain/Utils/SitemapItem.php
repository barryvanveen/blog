<?php

declare(strict_types=1);

namespace App\Domain\Utils;

use DateTimeInterface;

final class SitemapItem
{
    private string $url;

    private DateTimeInterface $lastModificationDate;

    public function __construct(
        string $url,
        DateTimeInterface $lastModificationDate,
    ) {
        $this->url = $url;
        $this->lastModificationDate = $lastModificationDate;
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
