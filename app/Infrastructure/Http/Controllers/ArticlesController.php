<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Core\CommandBusInterface;
use App\Application\Core\RecordNotFoundException;
use App\Application\Core\ResponseBuilderInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
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

    public function show(ArticleShowRequestInterface $request): ResponseInterface
    {
        try {
            $article = $this->articleRepository->getPublishedByUuid($request->uuid());
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
