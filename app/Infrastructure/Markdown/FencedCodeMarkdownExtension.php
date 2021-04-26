<?php

declare(strict_types=1);

namespace App\Infrastructure\Markdown;

use Highlight\Highlighter;
use cebe\markdown\GithubMarkdown;

class FencedCodeMarkdownExtension extends GithubMarkdown
{
    private Highlighter $highlighter;

    public function __construct()
    {
        $this->highlighter = new Highlighter();
        $this->highlighter->setAutodetectLanguages([
            'php',
            'js',
            'css',
            'bash',
        ]);
    }

    /**
     * @param string $line
     * @return bool
     */
    protected function identifyFencedCode($line)
    {
        // if a line starts with at least 3 backticks it is identified as a fenced code block
        if (strncmp($line, '```', 3) === 0) {
            return true;
        }
        return false;
    }

    /**
     * @param array $lines
     * @param int $current
     * @return array
     */
    protected function consumeFencedCode($lines, $current)
    {
        // create block array
        $block = [
            'fencedCode',
            'content' => [],
        ];
        $line = rtrim($lines[$current]);

        // detect language and fence length (can be more than 3 backticks)
        $fence = substr($line, 0, $pos = (int) strrpos($line, '`') + 1);
        $language = substr($line, $pos);
        if (!empty($language)) {
            $block['language'] = $language;
        }

        // consume all lines until ```
        for ($i = $current + 1, $count = count($lines); $i < $count; $i++) {
            if (rtrim($line = $lines[$i]) !== $fence) {
                $block['content'][] = $line;
            } else {
                // stop consuming when code block is over
                break;
            }
        }

        return [$block, $i];
    }

    /**
     * @param array $block
     * @return string
     * @throws \Exception
     */
    protected function renderFencedCode($block)
    {
        $contents = htmlspecialchars_decode(implode("\n", $block['content']) . "\n");

        $result = isset($block['language'])
            ? $this->highlighter->highlight($block['language'], $contents)
            : $this->highlighter->highlightAuto($contents);

        $code = $result->value;
        $language = $block['language'] ?? $result->language;

        return vsprintf('<pre><code class="%s hljs %s" data-lang="%s">%s</code></pre>', [
            'language-'.$language,
            $language,
            $language,
            $code,
        ]);
    }
}
