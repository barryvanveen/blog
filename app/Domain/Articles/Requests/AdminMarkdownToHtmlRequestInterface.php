<?php

declare(strict_types=1);

namespace App\Domain\Articles\Requests;

interface AdminMarkdownToHtmlRequestInterface
{
    public function markdown(): string;
}
