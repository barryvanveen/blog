<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\ViewModels\ArticlesIndexViewModel;
use App\Application\Articles\ViewModels\ArticlesItemViewModel;
use App\Application\Core\CommandBusInterface;
use App\Application\Core\ResponseBuilderInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;

final class ArticlesController
{
    /** @var ArticleRepositoryInterface */
    private $articleRepository;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var ResponseBuilderInterface */
    private $responseBuilder;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        CommandBusInterface $commandBus,
        ResponseBuilderInterface $responseBuilder
    ) {
        $this->articleRepository = $articleRepository;
        $this->commandBus = $commandBus;
        $this->responseBuilder = $responseBuilder;
    }

    public function index(): ResponseInterface
    {
        $viewModel = new ArticlesIndexViewModel($this->articleRepository);

        return $this->responseBuilder->ok('pages.articles.index', $viewModel->toArray());
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

        return $this->responseBuilder->redirect(302, 'articles.index');
    }

    public function show(string $uuid, string $slug): ResponseInterface
    {
        $viewModel = new ArticlesItemViewModel($this->articleRepository);

        // handle missing articles
        // redirect if slug is not correct

        return $this->responseBuilder->ok('pages.articles.show', $viewModel->toArray($uuid));
    }
}
