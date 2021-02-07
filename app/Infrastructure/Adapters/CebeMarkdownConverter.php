<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\MarkdownConverterInterface;
use cebe\markdown\GithubMarkdown;

class CebeMarkdownConverter implements MarkdownConverterInterface
{
    private GithubMarkdown $githubMarkdown;

    public function __construct(
        GithubMarkdown $githubMarkdown
    ) {
        $this->githubMarkdown = $githubMarkdown;
    }

    public function convertToHtml(string $markdown): string
    {
        $this->githubMarkdown->html5 = true;
        $this->githubMarkdown->keepListStartNumber = true;
        $this->githubMarkdown->enableNewlines = true; // only available for GithubMarkdown
        return $this->githubMarkdown->parse($markdown);
    }
}
