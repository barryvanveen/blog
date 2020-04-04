<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Articles\Requests\AdminArticleUpdateRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminArticleUpdateRequest extends AdminArticleCreateRequest implements AdminArticleUpdateRequestInterface
{
    public function uuid(): string
    {
        return $this->getRouteParameterAsString('uuid');
    }
}
