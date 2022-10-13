<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Comments\Commands\CreateComment;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\HoneypotException;
use App\Application\Http\StatusCode;
use App\Application\Interfaces\CommandBusInterface;
use App\Application\Interfaces\ConfigurationInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Comments\CommentStatus;
use App\Domain\Comments\Requests\CommentStoreRequestInterface;
use DateTimeImmutable;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class CommentsController
{
    public function __construct(private ConfigurationInterface $configuration, private ArticleRepositoryInterface $articleRepository, private CommandBusInterface $commandBus, private ResponseBuilderInterface $responseBuilder, private LoggerInterface $logger)
    {
    }

    public function store(CommentStoreRequestInterface $request): ResponseInterface
    {
        if ($this->configuration->boolean('comments.enabled') === false) {
            return $this->responseBuilder->json([
                'error' => 'Posting new comments is currently disabled.',
            ], StatusCode::STATUS_SERVICE_UNAVAILABLE);
        }

        try {
            if ($request->honeypot() !== '') {
                throw HoneypotException::honeypotNotEmpty();
            }

            // will throw an exception if no article can be found
            $this->articleRepository->getPublishedByUuid($request->articleUuid());

            $command = new CreateComment(
                $request->articleUuid(),
                $request->content(),
                new DateTimeImmutable(),
                $request->email(),
                $request->ip(),
                $request->name(),
                CommentStatus::published()
            );

            $this->commandBus->dispatch($command);
        } catch (Exception $exception) {
            $this->logger->error("Could not save comment", [
                'exception' => $exception,
            ]);

            return $this->responseBuilder->json([
                'error' => 'Comment could not be created.',
            ], StatusCode::STATUS_BAD_REQUEST);
        }

        return $this->responseBuilder->json([
            'success' => true,
        ], StatusCode::STATUS_OK);
    }
}
