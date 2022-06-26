<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\LastfmInterface;
use App\Domain\Music\Album;
use Barryvanveen\Lastfm\Constants;
use Barryvanveen\Lastfm\Lastfm;
use Psr\Log\LoggerInterface;
use Throwable;

class LastfmHttpClient implements LastfmInterface
{
    public function __construct(
        private ConfigurationInterface $configuration,
        private Lastfm $lastfm,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @return Album[]
     */
    public function topAlbumsForLastMonth(): array
    {
        $username = $this->configuration->string('lastfm.username');

        try {
            $albums = $this->lastfm
                ->userTopAlbums($username)
                ->period(Constants::PERIOD_MONTH)
                ->limit(5)
                ->get();
        } catch (Throwable $exception) {
            $this->logger->error('Could not load top albums from last.fm', [
                'exception' => $exception,
            ]);

            return [];
        }

        return array_map(function (array $album) {
            return new Album(
                $album['artist']['name'],
                $album['name'],
                $album['image'][1]['#text'] ?? null,
            );
        }, $albums);
    }
}
