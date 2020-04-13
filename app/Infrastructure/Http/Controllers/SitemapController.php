<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use Psr\Http\Message\ResponseInterface;

class SitemapController
{
    /** @var ResponseBuilderInterface */
    private $responseBuilder;

    public function __construct(
        ResponseBuilderInterface $responseBuilder
    ) {
        $this->responseBuilder = $responseBuilder;
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->ok('pages.sitemap');
    }
}
