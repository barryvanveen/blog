<?php

declare(strict_types=1);

namespace App\Application\Core;

interface UniqueIdGeneratorInterface
{
    public function generate(): string;
}
