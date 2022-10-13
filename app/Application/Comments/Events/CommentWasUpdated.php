<?php

declare(strict_types=1);

namespace App\Application\Comments\Events;

use App\Application\Core\EventInterface;

class CommentWasUpdated implements EventInterface
{
    public function __construct(
        private string $uuid,
    ) {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
