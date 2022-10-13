<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\SessionInterface;

final class CsrfTokenPresenter implements PresenterInterface
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function present(): array
    {
        return [
            'token' => $this->session->token(),
        ];
    }
}
