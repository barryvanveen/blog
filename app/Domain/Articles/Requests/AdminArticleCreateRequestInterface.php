<?php

declare(strict_types=1);

namespace App\Domain\Articles\Requests;

interface AdminArticleCreateRequestInterface
{
    public function title(): string;

    public function publishedAt(): string;

    public function description(): string;

    public function content(): string;

    public function status(): int;
}
