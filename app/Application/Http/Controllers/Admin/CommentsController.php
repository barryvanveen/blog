<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Core\ResponseBuilderInterface;
use Psr\Http\Message\ResponseInterface;

final class CommentsController
{
    private ResponseBuilderInterface $responseBuilder;

    public function __construct(
        ResponseBuilderInterface $responseBuilder
    ) {
        $this->responseBuilder = $responseBuilder;
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->ok('comments.admin.index');
    }
}
