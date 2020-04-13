<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Application\Auth\Requests\LoginRequestInterface;

class LoginRequest extends BaseRequest implements LoginRequestInterface
{
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function email(): string
    {
        return $this->getInputParameterAsString('email');
    }

    public function password(): string
    {
        return $this->getInputParameterAsString('password');
    }

    public function remember(): bool
    {
        return $this->getFilledParameter('remember');
    }

    public function ip(): string
    {
        return (string) parent::ip();
    }
}
