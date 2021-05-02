<?php

declare(strict_types=1);

namespace App\Infrastructure\Markdown;

/**
 * With parse a markdown heading like '# My Heading {#my-id}'
 * Into the following html '<h1 id="my-id">My heading</h1>'
 */
trait HeadlineWithAttributes
{
    /** @var string */
    private $specialAttributesRegex = '\{(([#\.][A-z0-9-_]+\s*)+)\}';

    /**
     * Renders a headline with special attributes
     * @param array $block
     * @return string
     */
    protected function renderHeadline($block)
    {
        foreach ($block['content'] as $i => $element) {
            if ($element[0] === 'specialAttributes') {
                unset($block['content'][$i]);
                $block['attributes'] = $element[1];
            }
        }
        $tag = 'h' . $block['level'];
        $attributes = $this->renderAttributes($block);
        return "<$tag$attributes>" . rtrim($this->renderAbsy($block['content']), "# \t") . "</$tag>\n";
    }

    /**
     * @param array $block
     * @return string
     */
    protected function renderAttributes($block)
    {
        $html = [];
        if (isset($block['attributes'])) {
            $attributes = preg_split('/\s+/', $block['attributes'], -1, PREG_SPLIT_NO_EMPTY);
            foreach ($attributes as $attribute) {
                if ($attribute[0] === '#') {
                    $html['id'] = substr($attribute, 1);
                } else {
                    $html['class'][] = substr($attribute, 1);
                }
            }
        }
        $result = '';
        foreach ($html as $attr => $value) {
            if (is_array($value)) {
                $value = trim(implode(' ', $value));
            }
            if (!empty($value)) {
                $result .= " $attr=\"$value\"";
            }
        }
        return $result;
    }

    /**
     * @marker {
     */
    protected function parseSpecialAttributes($text)
    {
        if (preg_match("~$this->specialAttributesRegex~", $text, $matches)) {
            return [['specialAttributes', $matches[1]], strlen($matches[0])];
        }
        return [['text', '{'], 1];
    }

    /**
     * @param array $block
     * @return string
     */
    protected function renderSpecialAttributes($block)
    {
        return '{' . $block[1] . '}';
    }
}
