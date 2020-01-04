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
    /** @var GuardInterface */
    private $guard;

    /** @var SessionInterface */
    private $session;

    public function __construct(GuardInterface $guard, SessionInterface $session)
    {
        $this->guard = $guard;

        $this->session = $session;
    }

    /**
     * @param Login $command
     *
     * @return void
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
