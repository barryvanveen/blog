<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelRateLimiter;
use Illuminate\Cache\RateLimiter;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelRateLimiter
 */
class LaravelRateLimiterTest extends TestCase
{
    private const KEY = 'mykey';

    private LaravelRateLimiter $rateLimiter;

    public function setUp(): void
    {
        parent::setUp();

        /** @var RateLimiter $laravelRateLimiter */
        $laravelRateLimiter = $this->app->make(RateLimiter::class);

        $this->rateLimiter = new LaravelRateLimiter($laravelRateLimiter);
    }

    /**
     * @test
     *
     * @dataProvider tooManyAttemptsDataProvider
     */
    public function itTracksHitsAndChecksWhetherThereAreTooMany(int $hits, int $limit, bool $expected): void
    {
        for ($i = 0; $i < $hits; $i++) {
            $this->rateLimiter->hit(self::KEY);
        }

        $overLimit = $this->rateLimiter->tooManyAttempts(self::KEY, $limit);

        $this->assertEquals($expected, $overLimit);
    }

    public static function tooManyAttemptsDataProvider(): array
    {
        return [
            [
                'hits' => 1,
                'limit' => 5,
                'expected' => false,
            ],
            [
                'hits' => 1,
                'limit' => 1,
                'expected' => true,
            ],
            [
                'hits' => 2,
                'limit' => 1,
                'expected' => true,
            ],
            [
                'hits' => 2,
                'limit' => 2,
                'expected' => true,
            ],
            [
                'hits' => 2,
                'limit' => 3,
                'expected' => false,
            ],
        ];
    }

    /** @test */
    public function itClearsHits(): void
    {
        $this->rateLimiter->hit(self::KEY);

        $overLimit = $this->rateLimiter->tooManyAttempts(self::KEY, 1);

        $this->assertTrue($overLimit);

        $this->rateLimiter->clear(self::KEY);

        $overLimit = $this->rateLimiter->tooManyAttempts(self::KEY, 1);

        $this->assertFalse($overLimit);
    }

    /** @test */
    public function itReturnsTheLockoutInSeconds(): void
    {
        $this->rateLimiter->hit(self::KEY);

        $overLimit = $this->rateLimiter->tooManyAttempts(self::KEY, 1);

        $this->assertTrue($overLimit);

        $seconds = $this->rateLimiter->availableIn(self::KEY);

        $this->assertGreaterThan(0, $seconds);
    }

    /** @test */
    public function itReturnsTheLockoutInSecondsWithALongerDecay(): void
    {
        $this->rateLimiter->hit(self::KEY, 120);

        $overLimit = $this->rateLimiter->tooManyAttempts(self::KEY, 1);

        $this->assertTrue($overLimit);

        $seconds = $this->rateLimiter->availableIn(self::KEY);

        $this->assertGreaterThan(60, $seconds);
    }
}
