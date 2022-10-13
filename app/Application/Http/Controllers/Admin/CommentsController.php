<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Comments\Commands\CreateComment;
use App\Application\Comments\Commands\UpdateComment;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Interfaces\CommandBusInterface;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Comments\CommentStatus;
use App\Domain\Comments\Requests\AdminCommentCreateRequestInterface;
use App\Domain\Comments\Requests\AdminCommentEditRequestInterface;
use App\Domain\Comments\Requests\AdminCommentUpdateRequestInterface;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;

final class CommentsController
{
    public function __construct(private ResponseBuilderInterface $responseBuilder, private CommandBusInterface $commandBus, private CommentRepositoryInterface $commentRepository)
    {
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->ok('comments.admin.index');
    }

    public function create(): ResponseInterface
    {
        return $this->responseBuilder->ok('comments.admin.create');
    }

    public function store(AdminCommentCreateRequestInterface $request): ResponseInterface
    {
        $command = new CreateComment(
            $request->articleUuid(),
            $request->content(),
            new DateTimeImmutable($request->createdAt()),
            $request->email(),
            $request->ip(),
            $request->name(),
            new CommentStatus($request->status())
        );

        $this->commandBus->dispatch($command);

        return $this->responseBuilder->redirect('admin.comments.index');
    }

    public function edit(AdminCommentEditRequestInterface $request): ResponseInterface
    {
        try {
            $this->commentRepository->getByUuid($request->uuid());
        } catch (RecordNotFoundException $exception) {
            throw NotFoundHttpException::create($exception);
        }

        return $this->responseBuilder->ok('comments.admin.edit');
    }

    public function update(AdminCommentUpdateRequestInterface $request): ResponseInterface
    {
        $command = new UpdateComment(
            $request->articleUuid(),
            $request->content(),
            new DateTimeImmutable($request->createdAt()),
            $request->email(),
            $request->ip(),
            $request->name(),
            new CommentStatus($request->status()),
            $request->uuid()
        );

        $this->commandBus->dispatch($command);

        return $this->responseBuilder->redirect('admin.comments.index');
    }
}
