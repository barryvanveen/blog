<?php

declare(strict_types=1);

namespace App\Domain\Articles\Requests;

interface AdminArticleEditRequestInterface
{
    public function uuid(): string;
}
