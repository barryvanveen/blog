<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Interfaces\ConfigurationInterface;
use App\Infrastructure\Adapters\LaravelUrlGenerator;
use Illuminate\Contracts\Routing\UrlGenerator;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelUrlGenerator
 */
class LaravelUrlGeneratorTest extends TestCase
{
    private LaravelUrlGenerator $urlGenerator;

    private string $baseUrl;

    public function setUp(): void
    {
        parent::setUp();

        /** @var UrlGenerator $laravelUrlGenerator */
        $laravelUrlGenerator = $this->app->make(UrlGenerator::class);

        /** @var ConfigurationInterface $config */
        $config = $this->app->make(ConfigurationInterface::class);

        $this->urlGenerator = new LaravelUrlGenerator($laravelUrlGenerator);

        $this->baseUrl = $config->string('app.url');
    }

    /** @test */
    public function itGeneratesAnAbsoluteUrl(): void
    {
        $this->assertEquals(
            $this->baseUrl . '/about',
            $this->urlGenerator->route('about')
        );
    }

    /** @test */
    public function itGeneratesAnAbsoluteUrlWithGetParameters(): void
    {
        $this->assertEquals(
            $this->baseUrl . '/about?foo=bar',
            $this->urlGenerator->route(
                'about',
                [
                    'foo' => 'bar',
                ]
            )
        );
    }

    /** @test */
    public function itGeneratesAnAbsoluteUrlWithUrlParameters(): void
    {
        $this->assertEquals(
            $this->baseUrl . '/articles/123-foo',
            $this->urlGenerator->route(
                'articles.show',
                [
                    'uuid' => '123',
                    'slug' => 'foo',
                ]
            )
        );
    }

    /** @test */
    public function itGeneratesARelativeUrl(): void
    {
        $this->assertEquals(
            '/about',
            $this->urlGenerator->route(
                'about',
                [],
                false
            )
        );
    }

    /** @test */
    public function itGeneratesARelativeUrlWithGetParameters(): void
    {
        $this->assertEquals(
            '/about?foo=bar',
            $this->urlGenerator->route(
                'about',
                [
                    'foo' => 'bar',
                ],
                false
            )
        );
    }

    /** @test */
    public function itGeneratesARelativeUrlWithUrlParameters(): void
    {
        $this->assertEquals(
            '/articles/123-foo',
            $this->urlGenerator->route(
                'articles.show',
                [
                    'uuid' => '123',
                    'slug' => 'foo',
                ],
                false
            )
        );
    }
}
