<?php

declare(strict_types=1);

namespace App\Domain\Comments\Requests;

interface AdminCommentUpdateRequestInterface extends AdminCommentCreateRequestInterface
{
    public function uuid(): string;
}
