<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\ClockInterface;
use App\Application\Interfaces\LastfmInterface;
use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;

final class PagesMusicPresenter implements PresenterInterface
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private UrlGeneratorInterface $urlGenerator,
        private DateTimeFormatterInterface $dateTimeFormatter,
        private MarkdownConverterInterface $markdownConverter,
        private LastfmInterface $lastfm,
        private ClockInterface $clock,
    ) {
    }

    public function present(): array
    {
        $page = $this->pageRepository->music();

        return [
            'title' => $page->title(),
            'lastUpdatedDateInAtomFormat' => $this->dateTimeFormatter->metadata($this->clock->now()),
            'lastUpdatedDateInHumanFormat' => $this->dateTimeFormatter->humanReadable($this->clock->now()),
            'content' => $this->markdownConverter->convertToHtml($page->content()),
            'albums' => $this->lastfm->topAlbumsForLastMonth(),
            'metaData' => $this->buildMetaData($page),
        ];
    }

    private function buildMetaData(Page $page): MetaData
    {
        return new MetaData(
            $page->title(),
            $page->description(),
            $this->urlGenerator->route('music', [], true),
            MetaData::TYPE_WEBSITE
        );
    }
}
