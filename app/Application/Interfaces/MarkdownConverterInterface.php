<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface MarkdownConverterInterface
{
    public function convertToHtml(string $markdown): string;
}
