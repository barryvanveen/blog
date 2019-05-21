<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Articles\Models;

use App\Domain\Core\Enum;
use Tests\TestCase;

class FakeEnum extends Enum
{
    public const FOO = 0;
    public const BAR = 1;

    public static function foo(): self
    {
        return new self(self::FOO);
    }

    public static function bar(): self
    {
        return new self(self::BAR);
    }
}

/**
 * @covers \App\Domain\Core\Enum
 */
class EnumTest extends TestCase
{
    /** @test */
    public function itConstructsAnEnum(): void
    {
        $foo = FakeEnum::foo();
        $bar = FakeEnum::bar();

        $this->assertEquals(FakeEnum::FOO, $foo->getValue());
        $this->assertEquals(FakeEnum::BAR, $bar->getValue());
    }

    /** @test */
    public function itConvertsToString(): void
    {
        $foo = FakeEnum::foo();
        $bar = FakeEnum::bar();

        $this->assertEquals('0', (string) $foo);
        $this->assertEquals('1', (string) $bar);
    }

    /** @test */
    public function itChecksForEquality(): void
    {
        $foo = FakeEnum::foo();
        $bar = FakeEnum::bar();

        $this->assertTrue($foo->equals(FakeEnum::foo()));
        $this->assertFalse($foo->equals(FakeEnum::bar()));
        $this->assertFalse($bar->equals(FakeEnum::foo()));
        $this->assertTrue($bar->equals(FakeEnum::bar()));
    }
}
