<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Core;

use App\Application\Core\UniqueIdGenerator;
use Tests\TestCase;

/**
 * @covers \App\Application\Core\UniqueIdGenerator
 */
class UniqueIdGeneratorTest extends TestCase
{
    /** @test */
    public function itReturnsEightRandomCharacters(): void
    {
        $uniqueIdGenerator = new UniqueIdGenerator();

        $result = $uniqueIdGenerator->generate();

        $this->assertEquals(8, strlen($result));
    }

    /** @test */
    public function eachCallReturnsAUniqueString(): void
    {
        $uniqueIdGenerator = new UniqueIdGenerator();

        $result1 = $uniqueIdGenerator->generate();
        $result2 = $uniqueIdGenerator->generate();
        $result3 = $uniqueIdGenerator->generate();

        $this->assertNotEquals($result1, $result2);
        $this->assertNotEquals($result2, $result3);
        $this->assertNotEquals($result1, $result3);
    }
}
