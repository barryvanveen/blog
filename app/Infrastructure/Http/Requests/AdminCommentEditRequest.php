<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Comments\Requests\AdminCommentEditRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminCommentEditRequest extends BaseRequest implements AdminCommentEditRequestInterface
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
