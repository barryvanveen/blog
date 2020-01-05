<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelTranslator;
use Illuminate\Contracts\Translation\Translator;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelTranslator
 */
class LaravelTranslatorTest extends TestCase
{
    /** @var LaravelTranslator */
    private $translator;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Translator $laravelTranslator */
        $laravelTranslator = $this->app->make(Translator::class);

        $this->translator = new LaravelTranslator($laravelTranslator);
    }

    /** @test */
    public function itTranslates(): void
    {
        $this->assertEquals(
            'These credentials do not match our records.',
            $this->translator->get('auth.failed')
        );
    }

    /** @test */
    public function itTranslatesWithVariables(): void
    {
        $this->assertEquals(
            'Too many login attempts. Please try again in 123 seconds.',
            $this->translator->get('auth.throttle', [
                'seconds' => 123,
            ])
        );
    }

    /** @test */
    public function itPluralisesWithVariables(): void
    {
        $this->assertEquals(
            '1 comment',
            $this->translator->choice('blog.comments', 1, [
                'amount' => 1,
            ])
        );

        $this->assertEquals(
            '2 comments',
            $this->translator->choice('blog.comments', 2, [
                'amount' => 2,
            ])
        );
    }
}
