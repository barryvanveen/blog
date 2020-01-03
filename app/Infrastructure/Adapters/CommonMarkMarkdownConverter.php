<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\MarkdownConverterInterface;
use League\CommonMark\CommonMarkConverter;

class CommonMarkMarkdownConverter implements MarkdownConverterInterface
{
    /** @var CommonMarkConverter */
    private $commonMarkConverter;

    public function __construct(CommonMarkConverter $commonMarkConverter)
    {
        $this->commonMarkConverter = $commonMarkConverter;
    }

    public function convertToHtml(string $markdown): string
    {
        return $this->commonMarkConverter->convertToHtml($markdown);
    }
}
