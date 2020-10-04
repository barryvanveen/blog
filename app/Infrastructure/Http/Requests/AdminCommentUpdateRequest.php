<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Comments\Requests\AdminCommentUpdateRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminCommentUpdateRequest extends AdminCommentCreateRequest implements AdminCommentUpdateRequestInterface
{
    public function uuid(): string
    {
        return $this->getRouteParameterAsString('uuid');
    }
}
