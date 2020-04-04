<?php

declare(strict_types=1);

namespace App\Domain\Articles\Requests;

interface AdminArticleUpdateRequestInterface extends AdminArticleCreateRequestInterface
{
    public function uuid(): string;
}
