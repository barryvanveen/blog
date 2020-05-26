<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Application\Http\StatusCode;
use Psr\Http\Message\ResponseInterface;

interface ResponseBuilderInterface
{
    public function ok(string $view, array $data = []): ResponseInterface;

    public function redirect(
        string $route,
        array $routeParams = [],
        int $status = StatusCode::STATUS_FOUND
    ): ResponseInterface;

    public function redirectBack(
        int $status = StatusCode::STATUS_FOUND,
        array $errors = []
    ): ResponseInterface;

    public function redirectIntended(
        string $fallbackRoute,
        int $status = StatusCode::STATUS_FOUND
    ): ResponseInterface;

    public function xml(string $view): ResponseInterface;
}
