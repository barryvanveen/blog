<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Core\BaseCommandHandler;
use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\SessionInterface;

final class LogoutHandler extends BaseCommandHandler
{
    public function __construct(private GuardInterface $guard, private SessionInterface $session)
    {
    }

    public function handleLogout(): void
    {
        $this->guard->logout();

        $this->session->invalidate();
    }
}
