<?php

declare(strict_types=1);

namespace App\Infrastructure\Markdown;

use Highlight\Highlighter;

trait FencedCodeWithSyntaxHighlighting
{
    private $languages = [];

    /**
     * @param array $languages
     */
    protected function setAutoDetectLanguages($languages)
    {
        $this->languages = $languages;
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

        $highlighter = new Highlighter();
        if (!empty($this->languages)) {
            $highlighter->setAutodetectLanguages($this->languages);
        }

        $result = isset($block['language'])
            ? $highlighter->highlight($block['language'], $contents)
            : $highlighter->highlightAuto($contents);

        $code = $result->value;
        $language = isset($block['language'])
            ? $block['language']
            : $result->language;

        return vsprintf('<pre><code class="%s hljs %s" data-lang="%s" tabindex="0">%s</code></pre>', [
            'language-'.$language,
            $language,
            $language,
            $code,
        ]);
    }
}
