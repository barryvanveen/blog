<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface GuardInterface
{
    public function attempt(string $email, string $password): bool;

    public function logout(): void;
}
