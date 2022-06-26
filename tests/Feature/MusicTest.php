<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Application\Interfaces\LastfmInterface;
use App\Domain\Music\Album;
use App\Infrastructure\Adapters\LastfmHttpClient;
use Database\Factories\PageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

class MusicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeMusicPage(): void
    {
        PageFactory::new()
            ->title('Music')
            ->create();

        /** @var ObjectProphecy|LastfmInterface $lastfmHttpClient */
        $lastfmHttpClient = $this->prophesize(LastfmInterface::class);
        $lastfmHttpClient->topAlbumsForLastMonth()
            ->willReturn([
                new Album('FooArtist', 'FooAlbum', 'foo-image-url'),
            ]);

        $this->instance(
            LastfmHttpClient::class,
            $lastfmHttpClient->reveal(),
        );

        $response = $this->get(route('music'));

        $response->assertOk();
        $response->assertSee('FooArtist');
    }

    /** @test */
    public function seeMusicPageWitErrorMessage(): void
    {
        PageFactory::new()
            ->title('Music')
            ->create();

        /** @var ObjectProphecy|LastfmInterface $lastfmHttpClient */
        $lastfmHttpClient = $this->prophesize(LastfmInterface::class);
        $lastfmHttpClient->topAlbumsForLastMonth()
            ->willReturn([]);

        $this->instance(
            LastfmHttpClient::class,
            $lastfmHttpClient->reveal(),
        );

        $response = $this->get(route('music'));

        $response->assertOk();
        $response->assertSee(__('blog.no_music_found'));
    }
}
