<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Domain\Core\UniqueIdGeneratorInterface;

class UniqueIdGenerator implements UniqueIdGeneratorInterface
{
    public function generate(): string
    {
        return uniqid('', true);
    }
}
