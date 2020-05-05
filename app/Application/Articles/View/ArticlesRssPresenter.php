<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\DateTimeFormatterInterface;
use App\Application\View\PresenterInterface;
use DateTimeImmutable;

final class ArticlesRssPresenter implements PresenterInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var DateTimeFormatterInterface */
    private $dateTimeFormatter;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        DateTimeFormatterInterface $dateTimeFormatter
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->dateTimeFormatter = $dateTimeFormatter;
    }

    public function present(): array
    {
        return [
            'title' => 'Barry van Veen',
            'description' => 'Overview of all blog posts. Topics include Laravel Framework, web development, tips and tricks.',
            'site_url' => $this->urlGenerator->route('home'),
            'rss_url' => $this->urlGenerator->route('articles.rss'),
            'last_modified' => $this->dateTimeFormatter->metadata(new DateTimeImmutable()),
        ];
    }
}
