<?php

declare(strict_types=1);

namespace App\Domain\Utils;

use DateTimeInterface;

final class SitemapItemCollection
{
    /** @var SitemapItem[] */
    private array $items;

    public function __construct(
        SitemapItem ...$items,
    ) {
        $this->items = $items;
    }

    public function add(SitemapItem ...$items): void
    {
        $this->items = array_merge($this->items, $items);
    }

    public function items(): array
    {
        return $this->items;
    }

    public function lastPublicationDate(): ?DateTimeInterface
    {
        if (empty($this->items)) {
            return null;
        }

        return max(array_map(fn($item) => $item->lastModificationDate(), $this->items));
    }
}
