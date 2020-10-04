<?php

declare(strict_types=1);

namespace App\Domain\Comments\Requests;

interface AdminCommentCreateRequestInterface
{
    public function articleUuid(): string;

    public function content(): string;

    public function createdAt(): string;

    public function email(): string;

    public function ip(): string;

    public function name(): string;

    public function status(): int;
}
