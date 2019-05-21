<?php

declare(strict_types=1);

namespace App\Domain\Core;

interface UniqueIdGeneratorInterface
{
    public function generate(): string;
}
