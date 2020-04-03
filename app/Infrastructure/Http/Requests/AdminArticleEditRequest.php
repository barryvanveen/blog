<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Articles\Requests\AdminArticleEditRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminArticleEditRequest extends BaseRequest implements AdminArticleEditRequestInterface
{
    public function rules(): array
    {
        return [];
    }

    public function uuid(): string
    {
        return $this->getRouteParameterAsString('uuid');
    }
}
