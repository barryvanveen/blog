<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use Psr\Http\Message\ResponseInterface;

class SitemapController
{
    public function __construct(private ResponseBuilderInterface $responseBuilder)
    {
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->xml('pages.sitemap');
    }
}
