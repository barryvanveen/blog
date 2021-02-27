<?php

declare(strict_types=1);

namespace App\Application\Comments\Events;

use App\Application\Core\EventInterface;

class CommentWasCreated implements EventInterface
{
    /** @var string */
    private $uuid;

    public function __construct(
        string $uuid
    ) {
        $this->uuid = $uuid;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
