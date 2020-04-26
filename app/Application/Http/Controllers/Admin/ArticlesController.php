<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\Commands\UpdateArticle;
use App\Application\Core\RecordNotFoundException;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Interfaces\CommandBusInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Requests\AdminArticleCreateRequestInterface;
use App\Domain\Articles\Requests\AdminArticleEditRequestInterface;
use App\Domain\Articles\Requests\AdminArticleUpdateRequestInterface;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;

final class ArticlesController
{
    /** @var ArticleRepositoryInterface */
    private $articleRepository;

    /** @var ResponseBuilderInterface */
    private $responseBuilder;

    /** @var CommandBusInterface */
    private $commandBus;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        ResponseBuilderInterface $responseBuilder,
        CommandBusInterface $commandBus
    ) {
        $this->articleRepository = $articleRepository;
        $this->responseBuilder = $responseBuilder;
        $this->commandBus = $commandBus;
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->ok('pages.admin.articles.index');
    }

    public function create(): ResponseInterface
    {
        return $this->responseBuilder->ok('pages.admin.articles.create');
    }

    public function store(AdminArticleCreateRequestInterface $request): ResponseInterface
    {
        $command = new CreateArticle(
            $request->content(),
            $request->description(),
            new DateTimeImmutable($request->publishedAt()),
            new ArticleStatus($request->status()),
            $request->title()
        );

        $this->commandBus->dispatch($command);

        return $this->responseBuilder->redirect('admin.articles.index');
    }

    public function edit(AdminArticleEditRequestInterface $request): ResponseInterface
    {
        try {
            $this->articleRepository->getByUuid($request->uuid());
        } catch (RecordNotFoundException $exception) {
            throw NotFoundHttpException::create($exception);
        }

        return $this->responseBuilder->ok('pages.admin.articles.edit');
    }

    public function update(AdminArticleUpdateRequestInterface $request): ResponseInterface
    {
        $command = new UpdateArticle(
            $request->content(),
            $request->description(),
            new DateTimeImmutable($request->publishedAt()),
            new ArticleStatus($request->status()),
            $request->title(),
            $request->uuid()
        );

        $this->commandBus->dispatch($command);

        return $this->responseBuilder->redirect('admin.articles.index');
    }
}
