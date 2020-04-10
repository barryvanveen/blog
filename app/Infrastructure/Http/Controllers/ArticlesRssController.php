<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use Psr\Http\Message\ResponseInterface;

final class ArticlesRssController
{
    public function index(
        ResponseBuilderInterface $responseBuilder
    ): ResponseInterface {
        return $responseBuilder->xml('pages.articles.rss');
    }
}
