<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Domain\Users\Models\User;

interface GuardInterface
{
    public function attempt(string $email, string $password): bool;

    public function logout(): void;

    public function user(): User;
}
