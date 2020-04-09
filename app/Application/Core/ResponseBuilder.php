<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Interfaces\ViewBuilderInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    /** @var ViewBuilderInterface */
    private $viewBuilder;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        ViewBuilderInterface $viewBuilder,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->viewBuilder = $viewBuilder;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function ok(string $view, array $data = []): ResponseInterface
    {
        $view = $this->viewBuilder->render($view, $data);

        $body = $this->streamFactory->createStream($view);

        return $this->responseFactory->createResponse(200)->withBody($body);
    }

    public function redirect(int $status, string $route, array $routeParams = []): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($status);

        $location = $this->urlGenerator->route($route, $routeParams);

        return $response->withHeader('Location', $location);
    }

    public function xml(string $view): ResponseInterface
    {
        $view = $this->viewBuilder->render($view);

        $body = $this->streamFactory->createStream($view);

        return $this->responseFactory->createResponse(200)
            ->withBody($body)
            ->withHeader('Content-Type', 'text/xml');
    }
}
