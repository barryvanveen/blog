<?php

declare(strict_types=1);

namespace App\Application\Core;

use Psr\Http\Message\ResponseInterface;

interface ResponseBuilderInterface
{
    public function ok(string $view, array $data = []): ResponseInterface;

    public function redirect(int $code, string $route, array $routeParams = []): ResponseInterface;

    public function xml(string $view): ResponseInterface;
}
