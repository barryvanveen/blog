<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\View\DateTimeFormatter;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * @covers \App\Application\View\DateTimeFormatter
 */
class DateTimeFormatterTest extends TestCase
{
    /** @test */
    public function itFormatsDateTimes(): void
    {
        $datetime = new DateTimeImmutable('2020-05-05 23:30:00');
        $formatter = new DateTimeFormatter();

        $this->assertEquals('2020-05-05T23:30:00+02:00', $formatter->metadata($datetime));
        $this->assertEquals('May 05, 2020', $formatter->humanReadable($datetime));
    }
}
