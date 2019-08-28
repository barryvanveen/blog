<?php

declare(strict_types=1);

namespace App\Domain\Articles\Requests;

interface ArticleShowRequestInterface
{
    public function uuid(): string;

    public function slug(): string;
}
