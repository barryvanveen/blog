<?php

declare(strict_types=1);

namespace App\Domain\Users\Models;

class User
{
    public function __construct(
        private string $email,
        private string $name,
        private string $password,
        private ?string $rememberToken,
        private string $uuid,
    ) {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function rememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
