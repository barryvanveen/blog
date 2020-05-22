<?php

declare(strict_types=1);

namespace App\Domain\Pages\Requests;

interface AdminPageEditRequestInterface
{
    public function slug(): string;
}
