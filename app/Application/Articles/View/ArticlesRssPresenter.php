<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use DateTime;
use DateTimeInterface;

final class ArticlesRssPresenter implements PresenterInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    public function present(): array
    {
        return [
            'title' => 'Barry van Veen',
            'description' => 'Overview of all blog posts. Topics include Laravel Framework, web development, tips and tricks.',
            'site_url' => $this->urlGenerator->route('home'),
            'rss_url' => $this->urlGenerator->route('articles.rss'),
            'last_modified' => (new DateTime())->format(DateTimeInterface::ATOM),
        ];
    }
}
