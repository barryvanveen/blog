<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Application\Http\StatusCode;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Interfaces\ViewBuilderInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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

    /** @var ServerRequestInterface */
    private $request;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        ViewBuilderInterface $viewBuilder,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        UrlGeneratorInterface $urlGenerator,
        ServerRequestInterface $request,
        SessionInterface $session
    ) {
        $this->viewBuilder = $viewBuilder;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->urlGenerator = $urlGenerator;
        $this->request = $request;
        $this->session = $session;
    }

    public function ok(string $view, array $data = []): ResponseInterface
    {
        $view = $this->viewBuilder->render($view, $data);

        $body = $this->streamFactory->createStream($view);

        return $this->responseFactory->createResponse(StatusCode::STATUS_OK)->withBody($body);
    }

    public function redirect(
        string $route,
        array $routeParams = [],
        int $status = StatusCode::STATUS_FOUND
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse($status);

        $location = $this->urlGenerator->route($route, $routeParams);

        return $response->withHeader('Location', $location);
    }

    public function redirectBack(int $status = StatusCode::STATUS_FOUND): ResponseInterface
    {
        $location = $this->determinePreviousUrl();

        return $this->responseFactory->createResponse($status)
            ->withHeader('Location', $location);
    }

    public function redirectIntended(
        string $fallbackRoute,
        int $status = StatusCode::STATUS_FOUND
    ): ResponseInterface {
        $location = $this->session->intendedUrl() ?? $this->urlGenerator->route($fallbackRoute);

        return $this->responseFactory->createResponse($status)
            ->withHeader('Location', $location);
    }

    public function xml(string $view): ResponseInterface
    {
        $view = $this->viewBuilder->render($view);

        $body = $this->streamFactory->createStream($view);

        return $this->responseFactory->createResponse(StatusCode::STATUS_OK)
            ->withBody($body)
            ->withHeader('Content-Type', 'text/xml');
    }

    private function determinePreviousUrl(): string
    {
        $referrer = $this->request->hasHeader('referer') ? $this->request->getHeader('referer')[0] : null;

        $url = $referrer ?? $this->session->previousUrl();

        return $url ?? $this->urlGenerator->route('home');
    }
}
