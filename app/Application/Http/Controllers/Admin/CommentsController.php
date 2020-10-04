<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Comments\Commands\CreateComment;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Interfaces\CommandBusInterface;
use App\Domain\Comments\CommentStatus;
use App\Domain\Comments\Requests\AdminCommentCreateRequestInterface;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;

final class CommentsController
{
    private ResponseBuilderInterface $responseBuilder;
    private CommandBusInterface $commandBus;

    public function __construct(
        ResponseBuilderInterface $responseBuilder,
        CommandBusInterface $commandBus
    ) {
        $this->responseBuilder = $responseBuilder;
        $this->commandBus = $commandBus;
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
}
