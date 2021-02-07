<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\CebeMarkdownConverter;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;
use cebe\markdown\GithubMarkdown;

/**
 * @covers \App\Infrastructure\Adapters\CebeMarkdownConverter
 */
class CebeMarkdownConverterTest extends TestCase
{
    /** @var ObjectProphecy|GithubMarkdown */
    private $githubMarkdown;

    private CebeMarkdownConverter $cebeMarkdownConverter;

    public function setUp(): void
    {
        parent::setUp();

        $this->githubMarkdown = $this->prophesize(GithubMarkdown::class);

        $this->cebeMarkdownConverter = new CebeMarkdownConverter(
            $this->githubMarkdown->reveal()
        );
    }

    /** @test */
    public function itReturnsParsedResponse(): void
    {
        // arrange
        $htmlString = 'htmlString';
        $this->githubMarkdown->parse(Argument::any())->willReturn($htmlString);

        // act
        $html = $this->cebeMarkdownConverter->convertToHtml('markdown');

        // assert
        $this->assertEquals($htmlString, $html);
    }
}
