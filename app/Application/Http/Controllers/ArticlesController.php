<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Core\RecordNotFoundException;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Http\StatusCode;
use App\Application\Interfaces\CommandBusInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
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
            throw NotFoundHttpException::create($exception);
        }

        if ($article->slug() !== $request->slug()) {
            return $this->responseBuilder->redirect(
                'articles.show',
                [
                    'uuid' => $request->uuid(),
                    'slug' => $article->slug(),
                ],
                StatusCode::STATUS_MOVED_PERMANENTLY
            );
        }

        return $this->responseBuilder->ok('pages.articles.show');
    }
}
