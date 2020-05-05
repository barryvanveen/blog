<?php

declare(strict_types=1);

namespace App\Application\View;

use DateTimeInterface;

class DateTimeFormatter implements DateTimeFormatterInterface
{
    public function metadata(DateTimeInterface $dateTime): string
    {
        return $dateTime->format(DateTimeInterface::ATOM);
    }

    public function humanReadable(DateTimeInterface $dateTime): string
    {
        return $dateTime->format('M d, Y');
    }
}
