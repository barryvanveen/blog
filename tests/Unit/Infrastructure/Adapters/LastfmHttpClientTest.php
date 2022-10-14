<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Interfaces\ConfigurationInterface;
use App\Domain\Music\Album;
use App\Infrastructure\Adapters\LastfmHttpClient;
use Barryvanveen\Lastfm\Constants;
use Barryvanveen\Lastfm\Lastfm;
use Exception;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LastfmHttpClient
 */
class LastfmHttpClientTest extends TestCase
{
    private const USERNAME = 'foo';
    private const TOP_ALBUM_ARTIST = 'Hath';
    private const TOP_ALBUM_NAME = 'All That Was Promised';
    private const TOP_ALBUM_IMAGE = 'https://lastfm.freetls.fastly.net/i/u/64s/419434481c3ddb8d5ad9da35267fc960.jpg';
    private const ALBUM_DATA = [
        [
            'artist' => [
                'url' => 'https://www.last.fm/music/Hath',
                'name' => self::TOP_ALBUM_ARTIST,
                'mbid' => 'c0705d72-db56-45c6-9827-9be46e3ce30f',
            ],
            'image' => [
                [
                    'size' => 'small',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/34s/419434481c3ddb8d5ad9da35267fc960.jpg',
                ],
                [
                    'size' => 'medium',
                    '#text' => self::TOP_ALBUM_IMAGE,
                ],
                [
                    'size' => 'large',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/174s/419434481c3ddb8d5ad9da35267fc960.jpg',
                ],
                [
                    'size' => 'extralarge',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/300x300/419434481c3ddb8d5ad9da35267fc960.jpg',
                ],
            ],
            'mbid' => '9ae30deb-1bd2-41d1-bc61-78797f16e078',
            'url' => 'https://www.last.fm/music/Hath/All+That+Was+Promised',
            'playcount' => 29,
            '@attr' => [
                'rank' => 1,
            ],
            'name' => self::TOP_ALBUM_NAME,
        ],
        [
            'artist' => [
                'url' => 'https://www.last.fm/music/Derek+&+The+Dominos',
                'name' => 'Derek & The Dominos',
                'mbid' => ' ',
            ],
            'image' => [
                [
                    'size' => 'small',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/34s/fd44803f0e8fbb17d14d0a323b53ba3e.jpg',
                ],
                [
                    'size' => 'medium',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/64s/fd44803f0e8fbb17d14d0a323b53ba3e.jpg',
                ],
                [
                    'size' => 'large',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/174s/fd44803f0e8fbb17d14d0a323b53ba3e.jpg',
                ],
                [
                    'size' => 'extralarge',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/300x300/fd44803f0e8fbb17d14d0a323b53ba3e.jpg',
                ],
            ],
            'mbid' => ' ',
            'url' => 'https://www.last.fm/music/Derek+&+The+Dominos/Layla+And+Other+Assorted+Love+Songs+(Remastered+2010)',
            'playcount' => 24,
            '@attr' => [
                'rank' => 2,
            ],
            'name' => 'Layla And Other Assorted Love Songs (Remastered 2010)',
        ],
        [
            'artist' => [
                'url' => 'https://www.last.fm/music/Moondog',
                'name' => 'Moondog',
                'mbid' => '703879a0-d7ff-4b40-a3d2-8e58ad191c0a',
            ],
            'image' => [
                [
                    'size' => 'small',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/34s/fb0d452b5f2479e1ef795126ddbd016e.jpg',
                ],
                [
                    'size' => 'medium',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/64s/fb0d452b5f2479e1ef795126ddbd016e.jpg',
                ],
                [
                    'size' => 'large',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/174s/fb0d452b5f2479e1ef795126ddbd016e.jpg',
                ],
                [
                    'size' => 'extralarge',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/300x300/fb0d452b5f2479e1ef795126ddbd016e.jpg',
                ],
            ],
            'mbid' => '3669cdbf-5934-499a-975f-3102aedb9581',
            'url' => 'https://www.last.fm/music/Moondog/Moondog',
            'playcount' => 21,
            '@attr' => [
                'rank' => 3,
            ],
            'name' => 'Moondog',
        ],
        [
            'artist' => [
                'url' => 'https://www.last.fm/music/Sungazer',
                'name' => 'Sungazer',
                'mbid' => '21006fdb-2e22-4950-91ed-8575a1a96b58',
            ],
            'image' => [
                [
                    'size' => 'small',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/34s/ce5b5b9a828e75e464b14b325a4a74b4.png',
                ],
                [
                    'size' => 'medium',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/64s/ce5b5b9a828e75e464b14b325a4a74b4.png',
                ],
                [
                    'size' => 'large',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/174s/ce5b5b9a828e75e464b14b325a4a74b4.png',
                ],
                [
                    'size' => 'extralarge',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/300x300/ce5b5b9a828e75e464b14b325a4a74b4.png',
                ],
            ],
            'mbid' => 'a8ff2ee7-b1e9-40f0-b2d7-870ef5e78344',
            'url' => 'https://www.last.fm/music/Sungazer/Perihelion',
            'playcount' => 21,
            '@attr' => [
                'rank' => 4,
            ],
            'name' => 'Perihelion',
        ],
        [
            'artist' => [
                'url' => 'https://www.last.fm/music/Russian+Circles',
                'name' => 'Russian Circles',
                'mbid' => 'cca40a67-e959-46f8-a3f3-8a3f77d2da9e',
            ],
            'image' => [
                [
                    'size' => 'small',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/34s/cc88a86d05fdf275c2533c1937ca4db6.jpg',
                ],
                [
                    'size' => 'medium',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/64s/cc88a86d05fdf275c2533c1937ca4db6.jpg',
                ],
                [
                    'size' => 'large',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/174s/cc88a86d05fdf275c2533c1937ca4db6.jpg',
                ],
                [
                    'size' => 'extralarge',
                    '#text' => 'https://lastfm.freetls.fastly.net/i/u/300x300/cc88a86d05fdf275c2533c1937ca4db6.jpg',
                ],
            ],
            'mbid' => '9a979aaa-f601-4341-b396-e90b09533c4c',
            'url' => 'https://www.last.fm/music/Russian+Circles/Guidance',
            'playcount' => 18,
            '@attr' => [
                'rank' => 5,
            ],
            'name' => 'Guidance',
        ],
    ];

    private ObjectProphecy|ConfigurationInterface $configuration;
    private ObjectProphecy|Lastfm $lastfm;
    private ObjectProphecy|LoggerInterface $logger;

    public function setUp(): void
    {
        $this->configuration = $this->prophesize(ConfigurationInterface::class);
        $this->configuration->string('lastfm.username')->willReturn(self::USERNAME);

        $this->lastfm = $this->prophesize(Lastfm::class);

        $this->logger = $this->prophesize(LoggerInterface::class);
    }

    /** @test */
    public function isRetrievesTopAlbumsForLastMonth(): void
    {
        // Arrange
        $this->lastfm->userTopAlbums(self::USERNAME)
            ->shouldBeCalled()
            ->willReturn($this->lastfm->reveal());

        $this->lastfm->period(Constants::PERIOD_MONTH)
            ->shouldBeCalled()
            ->willReturn($this->lastfm->reveal());

        $this->lastfm->limit(5)
            ->shouldBeCalled()
            ->willReturn($this->lastfm->reveal());

        $this->lastfm->get()
            ->shouldBeCalled()
            ->willReturn(self::ALBUM_DATA);

        $this->logger->error(Argument::any())
            ->shouldNotBeCalled();

        $lastfmClient = new LastfmHttpClient(
            $this->configuration->reveal(),
            $this->lastfm->reveal(),
            $this->logger->reveal(),
        );

        // act
        $data = $lastfmClient->topAlbumsForLastMonth();

        // assert
        $this->assertCount(5, $data);

        $topAlbum = $data[0];
        $this->assertInstanceOf(Album::class, $topAlbum);
        $this->assertEquals(self::TOP_ALBUM_ARTIST, $topAlbum->artist());
        $this->assertEquals(self::TOP_ALBUM_NAME, $topAlbum->name());
        $this->assertEquals(self::TOP_ALBUM_IMAGE, $topAlbum->image());
    }

    /** @test */
    public function isReturnsAnEmptyArrayIfTheClientThrowsErrors(): void
    {
        // Arrange
        $exception = new Exception('Whatever happened?');

        $this->lastfm->userTopAlbums(self::USERNAME)
            ->willThrow($exception);

        $this->logger->error('Could not load top albums from last.fm', [
                'exception' => $exception,
            ])
            ->shouldBeCalled();

        $lastfmClient = new LastfmHttpClient(
            $this->configuration->reveal(),
            $this->lastfm->reveal(),
            $this->logger->reveal(),
        );

        // act
        $lastfmClient->topAlbumsForLastMonth();

        // assert by perconditions
    }
}
