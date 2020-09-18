<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;

final class PagesAboutPresenter implements PresenterInterface
{
    /** @var PageRepositoryInterface */
    private $repository;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var DateTimeFormatterInterface */
    private $dateTimeFormatter;

    /** @var MarkdownConverterInterface */
    private MarkdownConverterInterface $markdownConverter;

    public function __construct(
        PageRepositoryInterface $repository,
        UrlGeneratorInterface $urlGenerator,
        DateTimeFormatterInterface $dateTimeFormatter,
        MarkdownConverterInterface $markdownConverter
    ) {
        $this->repository = $repository;
        $this->urlGenerator = $urlGenerator;
        $this->dateTimeFormatter = $dateTimeFormatter;
        $this->markdownConverter = $markdownConverter;
    }

    public function present(): array
    {
        $page = $this->repository->about();

        return [
            'title' => $page->title(),
            'lastUpdatedDateInAtomFormat' => $this->lastUpdateInAtomFormat($page),
            'lastUpdatedDateInHumanFormat' => $this->lastUpdateInHumanFormat($page),
            'content' => $this->markdownConverter->convertToHtml($page->content()),
            'metaData' => $this->buildMetaData($page),
        ];
    }

    private function lastUpdateInAtomFormat(Page $page): string
    {
        return $this->dateTimeFormatter->metadata($page->lastUpdated());
    }

    private function lastUpdateInHumanFormat(Page $page): string
    {
        return $this->dateTimeFormatter->humanReadable($page->lastUpdated());
    }

    private function buildMetaData(Page $page): MetaData
    {
        return new MetaData(
            $page->title(),
            $page->description(),
            $this->urlGenerator->route('about', [], true),
            MetaData::TYPE_WEBSITE
        );
    }
}
