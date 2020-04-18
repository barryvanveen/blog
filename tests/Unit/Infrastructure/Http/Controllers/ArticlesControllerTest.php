<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Http\Controllers;

use App\Application\Core\CommandBusInterface;
use App\Application\Core\RecordNotFoundException;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Http\Controllers\ArticlesController;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use DateTimeImmutable;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Tests\TestCase;

/**
 * @covers \App\Application\Http\Controllers\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    /** @var ObjectProphecy|ArticleRepositoryInterface */
    private $articleRepository;

    /** @var ObjectProphecy|CommandBusInterface */
    private $commandBus;

    /** @var ObjectProphecy|ResponseBuilderInterface */
    private $responseBuilder;

    /** @var ObjectProphecy|ArticlesController */
    private $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->responseBuilder = $this->prophesize(ResponseBuilderInterface::class);

        $this->controller = new \App\Application\Http\Controllers\ArticlesController(
            $this->articleRepository->reveal(),
            $this->commandBus->reveal(),
            $this->responseBuilder->reveal()
        );
    }

    /** @test */
    public function indexReturnsIndexViewResponse(): void
    {
        // arrange
        $response = $this->buildResponse('ok', 200);

        $this->responseBuilder
            ->ok(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->index();

        // assert
        $this->assertEquals($response, $result);
    }

    /** @test */
    public function showThrowsExceptionForMissingRecords(): void
    {
        // arrange
        $request = $this->prophesize(ArticleShowRequestInterface::class);

        $request
            ->uuid()
            ->shouldBeCalled()
            ->willReturn('asdasd');

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact('asdasd'))
            ->shouldBeCalled()
            ->willThrow(RecordNotFoundException::emptyResultSet());

        // assert
        $this->expectException(NotFoundHttpException::class);

        // act
        $this->controller->show($request->reveal());
    }

    /** @test */
    public function showRedirectsOnSlugMismatch(): void
    {
        // arrange
        $request = $this->prophesize(ArticleShowRequestInterface::class);

        $request
            ->uuid()
            ->shouldBeCalled()
            ->willReturn('asdasd');

        $request
            ->slug()
            ->shouldBeCalled()
            ->willReturn('requestSlug');

        $article = new Article(
            'content',
            'description',
            new DateTimeImmutable(),
            'articleSlug',
            ArticleStatus::published(),
            'title',
            'uuid'
        );

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact('asdasd'))
            ->shouldBeCalled()
            ->willReturn($article);

        $response = $this->buildResponse('redirect', 301);

        $this->responseBuilder
            ->redirect(Argument::exact(301), Argument::type('string'), Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->show($request->reveal());

        $this->assertEquals($response, $result);
    }

    /** @test */
    public function showReturnsViewResponse(): void
    {
        // arrange
        $request = $this->prophesize(ArticleShowRequestInterface::class);

        $request
            ->uuid()
            ->shouldBeCalled()
            ->willReturn('asdasd');

        $request
            ->slug()
            ->shouldBeCalled()
            ->willReturn('slug');

        $article = new Article(
            'content',
            'description',
            new DateTimeImmutable(),
            'slug',
            ArticleStatus::published(),
            'title',
            'uuid'
        );

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact('asdasd'))
            ->shouldBeCalled()
            ->willReturn($article);

        $response = $this->buildResponse('ok', 200);

        $this->responseBuilder
            ->ok(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->show($request->reveal());

        $this->assertEquals($response, $result);
    }

    protected function buildResponse(string $body, int $code): ResponseInterface
    {
        return $this->getResponseFactory()
            ->createResponse($code)
            ->withBody(
                $this->getStreamFactory()->createStream($body)
            );
    }
}
