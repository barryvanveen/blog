<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Interfaces\CommandBusInterface;
use App\Application\Pages\Commands\CreatePage;
use App\Application\Pages\Commands\UpdatePage;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Pages\Requests\AdminPageCreateRequestInterface;
use App\Domain\Pages\Requests\AdminPageEditRequestInterface;
use App\Domain\Pages\Requests\AdminPageUpdateRequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PagesController
{
    /** @var PageRepositoryInterface */
    private $pageRepository;

    /** @var ResponseBuilderInterface */
    private $responseBuilder;

    /** @var CommandBusInterface */
    private $commandBus;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        ResponseBuilderInterface $responseBuilder,
        CommandBusInterface $commandBus
    ) {
        $this->pageRepository = $pageRepository;
        $this->responseBuilder = $responseBuilder;
        $this->commandBus = $commandBus;
    }

    public function index(): ResponseInterface
    {
        return $this->responseBuilder->ok('pages.admin.index');
    }

    public function create(): ResponseInterface
    {
        return $this->responseBuilder->ok('pages.admin.create');
    }

    public function store(AdminPageCreateRequestInterface $request): ResponseInterface
    {
        $command = new CreatePage(
            $request->content(),
            $request->description(),
            $request->slug(),
            $request->title()
        );

        $this->commandBus->dispatch($command);

        return $this->responseBuilder->redirect('admin.pages.index');
    }

    public function edit(AdminPageEditRequestInterface $request): ResponseInterface
    {
        try {
            $this->pageRepository->getBySlug($request->slug());
        } catch (RecordNotFoundException $exception) {
            throw NotFoundHttpException::create($exception);
        }

        return $this->responseBuilder->ok('pages.admin.edit');
    }

    public function update(AdminPageUpdateRequestInterface $request): ResponseInterface
    {
        $command = new UpdatePage(
            $request->content(),
            $request->description(),
            $request->slug(),
            $request->title()
        );

        $this->commandBus->dispatch($command);

        return $this->responseBuilder->redirect('admin.pages.index');
    }
}
