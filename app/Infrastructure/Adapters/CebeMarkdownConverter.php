<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\MarkdownConverterInterface;
use App\Infrastructure\Markdown\FencedCodeMarkdownExtension;

class CebeMarkdownConverter implements MarkdownConverterInterface
{
    private FencedCodeMarkdownExtension $markdownConverter;

    public function __construct(FencedCodeMarkdownExtension $fencedCodeMarkdownExtension)
    {
        $this->markdownConverter = $fencedCodeMarkdownExtension;
    }

    public function convertToHtml(string $markdown): string
    {
        $this->markdownConverter->html5 = true;
        $this->markdownConverter->keepListStartNumber = true;
        $this->markdownConverter->enableNewlines = true; // only available for GithubMarkdown
        return $this->markdownConverter->parse($markdown);
    }
}
