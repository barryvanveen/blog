<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Core\BaseCommandHandler;
use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\SessionInterface;

final class LoginHandler extends BaseCommandHandler
{
    public function __construct(private GuardInterface $guard, private SessionInterface $session)
    {
    }

    /**
     *
     *
     * @throws FailedLoginException
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
        return $this->guard->attempt($command->email, $command->password);
    }

    private function login(): void
    {
        $this->session->regenerate();
    }

    private function getFailedException(): FailedLoginException
    {
        return FailedLoginException::credentialsDontMatch();
    }
}
