<?php

declare(strict_types=1);

namespace App\Application\Auth\Handlers;

use App\Application\Core\BaseCommandHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;

final class LogoutHandler extends BaseCommandHandler
{
    /** @var Store */
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function handleLogout(): void
    {
        Auth::guard()->logout();

        $this->session->invalidate();
    }
}
