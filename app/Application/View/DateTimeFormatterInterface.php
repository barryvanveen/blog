<?php

declare(strict_types=1);

namespace App\Application\View;

use DateTimeInterface;

interface DateTimeFormatterInterface
{
    public function metadata(DateTimeInterface $dateTime): string;

    public function humanReadable(DateTimeInterface $dateTime): string;
}
