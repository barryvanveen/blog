<?php

declare(strict_types=1);

namespace App\Domain\Users\Models;

class User
{
    /** @var string */
    private $email;

    /** @var string */
    private $name;

    /** @var string */
    private $password;

    /** @var string|null */
    private $rememberToken;

    /** @var string*/
    private $uuid;

    public function __construct(
        string $email,
        string $name,
        string $password,
        ?string $rememberToken,
        string $uuid
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->rememberToken = $rememberToken;
        $this->uuid = $uuid;
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
