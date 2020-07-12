<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\MarkdownConverterInterface;
use cebe\markdown\GithubMarkdown;

class CebeMarkdownConverter implements MarkdownConverterInterface
{
    /** @var GithubMarkdown */
    private $markdownConverter;

    public function __construct(GithubMarkdown $markdownConverter)
    {
        $this->markdownConverter = $markdownConverter;
    }

    public function convertToHtml(string $markdown): string
    {
        $this->markdownConverter->html5 = true;
        $this->markdownConverter->keepListStartNumber = true;
        $this->markdownConverter->enableNewlines = true;

        return $this->markdownConverter->parse($markdown);
    }
}
