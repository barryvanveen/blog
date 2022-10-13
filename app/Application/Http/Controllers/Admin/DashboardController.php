<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Core\ResponseBuilderInterface;
use Psr\Http\Message\ResponseInterface;

class DashboardController
{
    public function __construct(private ResponseBuilderInterface $responseBuilder)
    {
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->ok('pages.admin.dashboard');
    }
}
