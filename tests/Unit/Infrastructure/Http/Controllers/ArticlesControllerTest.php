<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Http\Controllers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Core\CommandBusInterface;
use App\Application\Core\RecordNotFoundException;
use App\Application\Core\ResponseBuilderInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use App\Infrastructure\Http\Controllers\ArticlesController;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tests\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\StreamFactory;

/**
 * @covers \App\Infrastructure\Http\Controllers\ArticlesController
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

        $this->controller = new ArticlesController(
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
    public function storeDispatchesCreateArticleCommandAndRedirects(): void
    {
        // arrange
        $this->commandBus
            ->dispatch(Argument::type(CreateArticle::class))
            ->shouldBeCalled();

        $response = $this->buildResponse('redirect', 302);

        $this->responseBuilder
            ->redirect(Argument::exact(302), Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->store();

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
            ->getByUuid(Argument::exact('asdasd'))
            ->shouldBeCalled()
            ->willThrow(RecordNotFoundException::emptyResultSet());

        // assert
        $this->expectException(ModelNotFoundException::class);

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
            'authorUuid',
            'content',
            'description',
            new DateTimeImmutable(),
            'articleSlug',
            ArticleStatus::published(),
            'title',
            'uuid'
        );

        $this->articleRepository
            ->getByUuid(Argument::exact('asdasd'))
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
            'authorUuid',
            'content',
            'description',
            new DateTimeImmutable(),
            'slug',
            ArticleStatus::published(),
            'title',
            'uuid'
        );

        $this->articleRepository
            ->getByUuid(Argument::exact('asdasd'))
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

    protected function buildStream(string $contents): StreamInterface
    {
        $factory = new StreamFactory();

        return $factory->createStream($contents);
    }

    protected function buildResponse(string $body, int $code): ResponseInterface
    {
        $stream = $this->buildStream($body);

        return new Response($stream, $code);
    }
}