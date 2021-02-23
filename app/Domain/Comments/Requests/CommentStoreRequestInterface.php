<?php

declare(strict_types=1);

namespace App\Domain\Comments\Requests;

interface CommentStoreRequestInterface
{
    public function articleUuid(): string;

    public function content(): string;

    public function email(): string;

    public function ip(): string;

    public function name(): string;
}
