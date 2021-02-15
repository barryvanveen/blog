<?php

declare(strict_types=1);

namespace App\Application\Pages\View;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Utils\MetaData;
use Psr\Log\LoggerInterface;

final class PagesHomePresenter implements PresenterInterface
{
    private const ARTICLES_PLACEHOLDER = "{{ARTICLES}}";
    private const BOOKS_PLACEHOLDER = '{{BOOKS}}';
    private const BOOKS_CURRENTLY_READING_START = '## Currently reading:';

    private PageRepositoryInterface $pageRepository;
    private UrlGeneratorInterface $urlGenerator;
    private DateTimeFormatterInterface $dateTimeFormatter;
    private MarkdownConverterInterface $markdownConverter;
    private ArticleRepositoryInterface $articleRepository;
    private LoggerInterface $logger;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        UrlGeneratorInterface $urlGenerator,
        DateTimeFormatterInterface $dateTimeFormatter,
        MarkdownConverterInterface $markdownConverter,
        ArticleRepositoryInterface $articleRepository,
        LoggerInterface $logger
    ) {
        $this->pageRepository = $pageRepository;
        $this->urlGenerator = $urlGenerator;
        $this->dateTimeFormatter = $dateTimeFormatter;
        $this->markdownConverter = $markdownConverter;
        $this->articleRepository = $articleRepository;
        $this->logger = $logger;
    }

    public function present(): array
    {
        $page = $this->pageRepository->home();

        return [
            'title' => $page->title(),
            'lastUpdatedDateInAtomFormat' => $this->lastUpdateInAtomFormat($page),
            'lastUpdatedDateInHumanFormat' => $this->lastUpdateInHumanFormat($page),
            'content' => $this->getContent($page),
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
            $this->urlGenerator->route('home', [], true),
            MetaData::TYPE_WEBSITE
        );
    }

    private function getContent(Page $page): string
    {
        $homePageContent = $page->content();

        $homePageContent = $this->replaceRecentArticles($homePageContent);

        $homePageContent = $this->replaceBooks($homePageContent);

        return $this->markdownConverter->convertToHtml($homePageContent);
    }

    private function replaceRecentArticles(string $homePageContent): string
    {
        if (mb_strpos($homePageContent, self::ARTICLES_PLACEHOLDER) === false) {
            return $homePageContent;
        }

        $articles = $this->articleRepository->allPublishedAndOrdered();

        if ($articles->count() === 0) {
            $this->logger->error("Could not find articles for home page");
            return str_replace(self::ARTICLES_PLACEHOLDER, "That's strange, no articles yet...\n", $homePageContent);
        }

        $articlesContent = '';
        $counter = 0;
        foreach ($articles as $article) {
            $articleUrl = $this->urlGenerator->route('articles.show', ['uuid' => $article->uuid(), 'slug' => $article->slug()]);
            $articlesContent .= "- [".$article->title()."](".$articleUrl.")\n";
            $counter++;
            if ($counter >= 3) {
                break;
            }
        }

        return str_replace(self::ARTICLES_PLACEHOLDER, $articlesContent, $homePageContent);
    }

    private function replaceBooks(string $homePageContent): string
    {
        if (mb_strpos($homePageContent, self::BOOKS_PLACEHOLDER) === false) {
            return $homePageContent;
        }

        $booksContent = $this->pageRepository->books()->content();

        $needleLength = mb_strlen(self::BOOKS_CURRENTLY_READING_START);
        $currentlyReadingStart = mb_strpos($booksContent, self::BOOKS_CURRENTLY_READING_START);

        if ($currentlyReadingStart === false) {
            $this->logger->error('Could not parse books text (start) for homepage');
            return str_replace(self::BOOKS_PLACEHOLDER, "Not reading anything, actually\n", $homePageContent);
        }

        $currentlyReadingStart += $needleLength;
        $currentlyReadingEnd = mb_strpos($booksContent, '## ', $currentlyReadingStart);

        if ($currentlyReadingEnd === false) {
            $this->logger->error('Could not parse books text (end) for homepage');
            return str_replace(self::BOOKS_PLACEHOLDER, "Not reading anything, actually\n", $homePageContent);
        }

        $currentlyReadingContents = mb_substr($booksContent, $currentlyReadingStart, $currentlyReadingEnd-$currentlyReadingStart);

        return str_replace(self::BOOKS_PLACEHOLDER, $currentlyReadingContents, $homePageContent);
    }
}
