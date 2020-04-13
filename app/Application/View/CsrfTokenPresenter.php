<?php

declare(strict_types=1);

namespace App\Application\View;

use App\Application\Interfaces\SessionInterface;

final class CsrfTokenPresenter implements PresenterInterface
{
    /** @var SessionInterface */
    private $session;

    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }

    public function present(): array
    {
        return [
            'token' => $this->session->token(),
        ];
    }
}
