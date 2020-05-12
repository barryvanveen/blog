<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Interfaces\CommandBusInterface;
use App\Domain\Pages\PageRepositoryInterface;
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
}
