<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\CebeMarkdownConverter;
use App\Infrastructure\Markdown\MyMarkdown;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\CebeMarkdownConverter
 */
class CebeMarkdownConverterTest extends TestCase
{
    private ObjectProphecy|MyMarkdown $extendedGithubMarkdown;

    private CebeMarkdownConverter $cebeMarkdownConverter;

    public function setUp(): void
    {
        parent::setUp();

        $this->extendedGithubMarkdown = $this->prophesize(MyMarkdown::class);

        $this->cebeMarkdownConverter = new CebeMarkdownConverter(
            $this->extendedGithubMarkdown->reveal()
        );
    }

    /** @test */
    public function itReturnsParsedResponse(): void
    {
        // arrange
        $htmlString = 'htmlString';
        $this->extendedGithubMarkdown->parse(Argument::any())->willReturn($htmlString);

        // act
        $html = $this->cebeMarkdownConverter->convertToHtml('markdown');

        // assert
        $this->assertEquals($htmlString, $html);
    }
}
