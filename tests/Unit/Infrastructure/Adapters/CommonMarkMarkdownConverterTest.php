<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\CommonMarkMarkdownConverter;
use League\CommonMark\CommonMarkConverter;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\CommonMarkMarkdownConverter
 */
class CommonMarkMarkdownConverterTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider dataProvider
     */
    public function itConvertsMarkdownToHtml(string $input, string $expected): void
    {
        $converter = new CommonMarkMarkdownConverter(
            new CommonMarkConverter()
        );

        $this->assertEquals($expected, $converter->convertToHtml($input));
    }

    public function dataProvider(): array
    {
        return [
            [
                'paragraph',
                "<p>paragraph</p>\n",
            ],
            [
                '# H1 header',
                "<h1>H1 header</h1>\n",
            ],
            [
                '## H2 header',
                "<h2>H2 header</h2>\n",
            ],
            [
                '__strong text__',
                "<p><strong>strong text</strong></p>\n",
            ],
            [
                '_italic text_',
                "<p><em>italic text</em></p>\n",
            ],
            [
                '* First item
* Second item
* Third item',
                "<ul>
<li>First item</li>
<li>Second item</li>
<li>Third item</li>
</ul>\n",
            ],
            [
                '[link text](http://linkhref)',
                "<p><a href=\"http://linkhref\">link text</a></p>\n",
            ],
            [
                'paragraph with `inline code` in it',
                "<p>paragraph with <code>inline code</code> in it</p>\n",
            ],
            [
                '```php
class MyFooClass
{
    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
```',
                "<pre><code class=\"language-php\">class MyFooClass
{
    public function __construct(int \$value)
    {
        \$this-&gt;value = \$value;
    }
}
</code></pre>\n",
            ],
            [
                '<img src="images/dropbox-app-aanmaken.png?w=320" alt="Dropbox app aanmaken">',
                "<img src=\"images/dropbox-app-aanmaken.png?w=320\" alt=\"Dropbox app aanmaken\">\n",
            ],
            [
                '<figcaption>Dropbox app details bekijken</figcaption>',
                "<figcaption>Dropbox app details bekijken</figcaption>\n",
            ],
        ];
    }
}
