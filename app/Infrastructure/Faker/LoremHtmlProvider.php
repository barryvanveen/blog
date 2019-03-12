<?php

declare(strict_types=1);

namespace App\Infrastructure\Faker;

use Faker\Provider\Lorem;

class LoremHtmlProvider extends Lorem
{
    /**
     * Generate html paragraphs.
     *
     * @example '<p>Lorem ipsum</p><p>Dolores delectus</p>'
     *
     * @param int  $nbParagraphs
     *
     * @return string
     */
    public static function htmlParagraphs($nbParagraphs = 3): string
    {
        $paragraphs = (array) static::paragraphs($nbParagraphs);

        foreach ($paragraphs as $key => $paragraph) {
            $paragraphs[$key] = '<p>'.$paragraph.'</p>';
        }

        return implode("\n\n", $paragraphs);
    }

    /**
     * Generate a single html paragraph.
     *
     * @example '<p>Lorem ipsum</p>'
     *
     * @param int  $nbSentences
     * @param bool $variableNbSentences
     *
     * @return string
     */
    public static function htmlParagraph($nbSentences = 3, $variableNbSentences = true): string
    {
        return '<p>'.static::paragraph($nbSentences, $variableNbSentences).'</p>';
    }

    /**
     * Generate an heading tag containing a sentence.
     *
     * @example '<h2>Lorem ipsum</h2>'
     *
     * @param int $headingSize
     *
     * @return string
     */
    public static function htmlHeading(int $headingSize = 2): string
    {
        return '<h'.$headingSize.'>'.static::sentence().'</h'.$headingSize.'>';
    }

    /**
     * Generate an ordered or unordered list of sentences.
     *
     * @example '<ul><li>Lorem ipsum</li><li>Lorem ipsum</li></ul>'
     *
     * @param int $nbItems
     *
     * @return string
     */
    public static function htmlList($nbItems = 3): string
    {
        $items = '';
        for ($i = 0; $i < $nbItems; ++$i) {
            $items .= '<li>'.static::sentence().'</li>';
        }

        if (self::randomFloat(null, 0, 1) <= 0.5) {
            return '<ol>'.$items.'</ol>';
        }

        return '<ul>'.$items.'</ul>';
    }

    /**
     * Generate a blockquote containing a sentence and possible an author (Bootstrap-style).
     *
     * @example '<blockquote>Lorem ipsum<footer>Cicero</footer>'
     *
     * @return string
     */
    public static function htmlBlockquote(): string
    {
        $quote = static::sentence();

        if (self::randomFloat(null, 0, 1) <= 0.5) {
            /**
             * @psalm-suppress PossiblyInvalidOperand
             */
            $quote .= '<footer>'.self::words(2, true).'</footer>';
        }

        return '<blockquote>'.$quote.'</blockquote>';
    }

    /**
     * Generate a code tag containing some sentences.
     *
     * @example '<code>Lorem ipsum</code>'
     *
     * @return string
     */
    public static function htmlCode(): string
    {
        /**
         * @psalm-suppress PossiblyInvalidArgument
         */
        return '<code>'.implode(' ', static::sentences()).'</code>';
    }

    /**
     * Generate html pre-tag containing some sentences.
     *
     * @example '<pre>Lorem ipsum</pre>'
     *
     * @return string
     */
    public static function htmlPre(): string
    {
        /**
         * @psalm-suppress PossiblyInvalidArgument
         */
        return '<pre>'.implode(' ', static::sentences()).'</pre>';
    }

    public static function htmlArticle(): string
    {
        $items = [
            static::htmlHeading(1),
            static::htmlParagraph(),
            static::htmlHeading(2),
            static::htmlParagraphs(1),
            static::htmlList(4),
            static::htmlHeading(2),
            static::htmlParagraphs(1),
            static::htmlCode(),
            static::htmlHeading(2),
            static::htmlParagraphs(1),
            static::htmlBlockquote(),
        ];

        return implode('', $items);
    }
}
