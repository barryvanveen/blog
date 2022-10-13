<?php

declare(strict_types=1);

namespace App\Application\Pages\Events;

use App\Application\Core\EventInterface;

class PageWasUpdated implements EventInterface
{
    public function __construct(private string $slug)
    {
    }

    public function slug(): string
    {
        return $this->slug;
    }
}
