<?php

declare(strict_types=1);

namespace App\Application\Auth\Requests;

interface LoginRequestInterface
{
    public function email(): string;

    public function password(): string;

    public function remember(): bool;

    public function ip(): string;
}
