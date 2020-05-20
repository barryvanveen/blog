<?php

declare(strict_types=1);

namespace App\Domain\Pages\Requests;

interface AdminPageCreateRequestInterface
{
    public function title(): string;

    public function slug(): string;

    public function description(): string;

    public function content(): string;
}
