<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Core\BaseCommandHandler;
use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\SessionInterface;

final class LogoutHandler extends BaseCommandHandler
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

    public function handleLogout(): void
    {
        $this->guard->logout();

        $this->session->invalidate();
    }
}
