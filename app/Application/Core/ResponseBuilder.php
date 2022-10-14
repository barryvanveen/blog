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
    public function __construct(
        private ViewBuilderInterface $viewBuilder,
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory,
        private UrlGeneratorInterface $urlGenerator,
        private ServerRequestInterface $request,
        private SessionInterface $session,
    ) {
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
        int $status = StatusCode::STATUS_FOUND,
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse($status);

        $location = $this->urlGenerator->route($route, $routeParams);

        return $response->withHeader('Location', $location);
    }

    public function redirectBack(
        int $status = StatusCode::STATUS_FOUND,
        array $errors = [],
    ): ResponseInterface {
        $location = $this->determinePreviousUrl();

        if (count($errors) > 0) {
            $this->session->flashErrors($errors);
        }

        return $this->responseFactory->createResponse($status)
            ->withHeader('Location', $location);
    }

    public function redirectIntended(
        string $fallbackRoute,
        int $status = StatusCode::STATUS_FOUND,
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

    public function json(array $data, int $status = StatusCode::STATUS_OK): ResponseInterface
    {
        $body = $this->streamFactory->createStream(json_encode($data, JSON_THROW_ON_ERROR));

        return $this->responseFactory->createResponse($status)
            ->withBody($body)
            ->withHeader('Content-Type', 'application/json');
    }

    private function determinePreviousUrl(): string
    {
        $referrer = $this->request->hasHeader('referer') ? $this->request->getHeader('referer')[0] : null;

        $url = $referrer ?? $this->session->previousUrl();

        return $url ?? $this->urlGenerator->route('home');
    }
}
