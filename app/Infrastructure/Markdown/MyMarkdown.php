<?php

declare(strict_types=1);

namespace App\Infrastructure\Markdown;

use cebe\markdown\GithubMarkdown;

class MyMarkdown extends GithubMarkdown
{
    use FencedCodeWithSyntaxHighlighting;
    use HeadlineWithAttributes;

    public function __construct()
    {
        $this->setAutoDetectLanguages([
            'php',
            'javascript',
            'css',
            'bash',
        ]);
    }
}
