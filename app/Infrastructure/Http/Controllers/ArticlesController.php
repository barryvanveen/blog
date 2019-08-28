<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Core\CommandBusInterface;
use App\Application\Core\RecordNotFoundException;
use App\Application\Core\ResponseBuilderInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        return $this->responseBuilder->ok('pages.articles.index');
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

    public function show(ArticleShowRequestInterface $request): ResponseInterface
    {
        try {
            $article = $this->articleRepository->getByUuid($request->uuid());
        } catch (RecordNotFoundException $exception) {
            throw new ModelNotFoundException();
        }

        if ($article->slug() !== $request->slug()) {
            return $this->responseBuilder->redirect(
                301,
                'articles.show',
                [
                    'uuid' => $request->uuid(),
                    'slug' => $article->slug(),
                ]
            );
        }

        return $this->responseBuilder->ok('pages.articles.show');
    }
}
