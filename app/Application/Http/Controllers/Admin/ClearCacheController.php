<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Interfaces\CacheInterface;
use App\Domain\Articles\Requests\AdminMarkdownToHtmlRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClearCacheController
{
    private CacheInterface $cache;
    private ResponseBuilderInterface $responseBuilder;

    public function __construct(
        CacheInterface $cache,
        ResponseBuilderInterface $responseBuilder,
    ) {
        $this->cache = $cache;
        $this->responseBuilder = $responseBuilder;
    }

    public function index(AdminMarkdownToHtmlRequestInterface $request): ResponseInterface
    {
        $this->cache->clear();

        return $this->responseBuilder->redirect('admin.dashboard');
    }
}
