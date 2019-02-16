<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Core\BaseCommandHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class LoginHandler extends BaseCommandHandler
{
    /** @var Store */
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * @param Login $command
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function handleLogin(Login $command): void
    {
        if ($this->attemptLogin($command)) {
            $this->login();
            return;
        }

        throw $this->getFailedException();
    }

    private function attemptLogin(Login $command): bool
    {
        return Auth::guard()->attempt(
            [
                'email' => $command->email,
                'password' => $command->password,
            ],
            $command->remember
        );
    }

    private function login(): void
    {
        $this->session->regenerate();
    }

    private function getFailedException(): ValidationException
    {
        return ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
