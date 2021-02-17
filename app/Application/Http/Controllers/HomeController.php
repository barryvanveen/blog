<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Domain\Pages\PageRepositoryInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    private PageRepositoryInterface $pageRepository;
    private ResponseBuilderInterface $responseBuilder;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        ResponseBuilderInterface $responseBuilder
    ) {
        $this->pageRepository = $pageRepository;
        $this->responseBuilder = $responseBuilder;
    }

    public function index(): ResponseInterface
    {
        try {
            $this->pageRepository->home();
        } catch (RecordNotFoundException $exception) {
            throw NotFoundHttpException::create($exception);
        }

        return $this->responseBuilder->ok('pages.home');
    }
}
