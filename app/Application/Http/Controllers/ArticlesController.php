<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Http\StatusCode;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ArticlesController
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseBuilderInterface $responseBuilder,
    ) {
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->ok('articles.index');
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

        return $this->responseBuilder->ok('articles.show');
    }
}
