<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Http\Controllers\HomeController;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Domain\Pages\PageRepositoryInterface;
use Nyholm\Psr7\Response;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Http\Controllers\HomeController
 */
class HomeControllerTest extends TestCase
{
    private ObjectProphecy|PageRepositoryInterface $repository;

    private ObjectProphecy|ResponseBuilderInterface $responseBuilder;

    private HomeController $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->prophesize(PageRepositoryInterface::class);
        $this->responseBuilder = $this->prophesize(ResponseBuilderInterface::class);

        $this->controller = new HomeController(
            $this->repository->reveal(),
            $this->responseBuilder->reveal()
        );
    }

    /** @test */
    public function itReturnsOkayIfPageExists(): void
    {
        $page = $this->getPage();

        $this->repository->home()
            ->willReturn($page);

        $this->responseBuilder->ok(Argument::type('string'))
            ->willReturn(new Response())
            ->shouldBeCalled();

        $this->controller->index();
    }

    /** @test */
    public function itThrowsExceptionIfPageDoesNotExist(): void
    {
        $this->repository->home()
            ->willThrow(new RecordNotFoundException());

        $this->responseBuilder->ok(Argument::type('string'))
            ->shouldNotBeCalled();

        $this->expectException(NotFoundHttpException::class);
        $this->controller->index();
    }
}
