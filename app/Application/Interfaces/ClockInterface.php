<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use DateTimeImmutable;

interface ClockInterface
{
    public function now(): DateTimeImmutable;
}
