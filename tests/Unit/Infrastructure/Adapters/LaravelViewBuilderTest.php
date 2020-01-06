<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelViewBuilder;
use Illuminate\Contracts\View\Factory;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelViewBuilder
 */
class LaravelViewBuilderTest extends TestCase
{
    /** @var LaravelViewBuilder */
    private $viewBuilder;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Factory $laravelViewBuilder */
        $laravelViewBuilder = $this->app->make(Factory::class);

        $this->viewBuilder = new LaravelViewBuilder($laravelViewBuilder);
    }

    /** @test */
    public function itRendersAView(): void
    {
        $this->assertEquals(
            "<h1>Foobar</h1>\n",
            $this->viewBuilder->render('testview', ['title' => 'Foobar'])
        );
    }
}
