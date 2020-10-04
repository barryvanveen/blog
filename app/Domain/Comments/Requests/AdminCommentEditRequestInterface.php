<?php

declare(strict_types=1);

namespace App\Domain\Comments\Requests;

interface AdminCommentEditRequestInterface
{
    public function uuid(): string;
}
