<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\ViewModels\ArticlesIndexViewModel;
use App\Application\Articles\ViewModels\ArticlesItemViewModel;
use App\Application\Core\CommandBusInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ArticlesController
{
    /** @var ArticleRepositoryInterface */
    private $articleRepository;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var Factory */
    private $viewFactory;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        CommandBusInterface $commandBus,
        Factory $factory,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->articleRepository = $articleRepository;
        $this->commandBus = $commandBus;
        $this->viewFactory = $factory;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function index(): ResponseInterface
    {
        $viewModel = new ArticlesIndexViewModel($this->articleRepository);

        $body = $this->viewFactory->make('pages.articles.index', $viewModel->toArray());

        return $this->successResponse($body);
    }

    private function successResponse(View $view): ResponseInterface
    {
        $body = $this->streamFactory->createStream($view->render());

        return $this->responseFactory->createResponse(200)->withBody($body);
    }

    public function store(): ResponseInterface
    {
        $command = new CreateArticle(
            '1',
            'baz',
            'bar',
            new DateTimeImmutable(),
            ArticleStatus::published(),
            'Foo title'
        );

        $this->commandBus->dispatch($command);

        return $this->redirectResponse(302, 'articles.index');
    }

    private function redirectResponse(int $status, string $route): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($status);

        return $response->withHeader('Location', route($route));
    }

    public function show(string $uuid, string $slug): ResponseInterface
    {
        $viewModel = new ArticlesItemViewModel($this->articleRepository);

        // handle missing articles
        // redirect if slug is not correct

        $body = $this->viewFactory->make('pages.articles.show', $viewModel->toArray($uuid));

        return $this->successResponse($body);
    }
}
