<?php

declare(strict_types=1);

namespace App\Application\Pages\Events;

use App\Application\Core\EventInterface;

class PageWasUpdated implements EventInterface
{
    /** @var string */
    private $slug;

    public function __construct(
        string $slug
    ) {
        $this->slug = $slug;
    }

    public function slug(): string
    {
        return $this->slug;
    }
}
