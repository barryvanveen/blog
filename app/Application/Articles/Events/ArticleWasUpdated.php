<?php

declare(strict_types=1);

namespace App\Application\Articles\Events;

use App\Application\Core\EventInterface;

class ArticleWasUpdated implements EventInterface
{
    public function __construct(private string $uuid)
    {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
