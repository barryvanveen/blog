<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;

class CsrfController
{
    public function __construct(private ResponseBuilderInterface $responseBuilder, private SessionInterface $session)
    {
    }

    public function csrf(): ResponseInterface
    {
        $token = $this->session->token();

        return $this->responseBuilder->json([
            'token' => $token,
        ]);
    }
}
