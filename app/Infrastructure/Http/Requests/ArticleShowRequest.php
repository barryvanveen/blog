<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Articles\Requests\ArticleShowRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class ArticleShowRequest extends BaseRequest implements ArticleShowRequestInterface
{
    public function rules(): array
    {
        return [];
    }

    public function uuid(): string
    {
        return $this->getRouteParameterAsString('uuid');
    }

    public function slug(): string
    {
        return $this->getRouteParameterAsString('slug');
    }
}
