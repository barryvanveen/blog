<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Controllers;

use App\Application\Comments\Commands\CreateComment;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Http\Controllers\CommentsController;
use App\Application\Http\StatusCode;
use App\Application\Interfaces\CommandBusInterface;
use App\Application\Interfaces\ConfigurationInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Comments\Requests\CommentStoreRequestInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * @covers \App\Application\Http\Controllers\CommentsController
 */
class CommentsControllerTest extends TestCase
{
    /** @var ObjectProphecy|ConfigurationInterface */
    private $configuration;

    /** @var ObjectProphecy|ArticleRepositoryInterface */
    private $articleRepository;

    /** @var ObjectProphecy|CommandBusInterface */
    private $commandBus;

    /** @var ObjectProphecy|ResponseBuilderInterface */
    private $responseBuilder;

    /** @var ObjectProphecy|LoggerInterface */
    private $logger;

    /** @var ObjectProphecy|CommentsController */
    private $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->configuration = $this->prophesize(ConfigurationInterface::class);
        $this->configuration->boolean('comments.enabled')->willReturn(true);
        $this->articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->responseBuilder = $this->prophesize(ResponseBuilderInterface::class);
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->controller = new CommentsController(
            $this->configuration->reveal(),
            $this->articleRepository->reveal(),
            $this->commandBus->reveal(),
            $this->responseBuilder->reveal(),
            $this->logger->reveal()
        );
    }

    /** @test */
    public function storeReturnsServiceUnavailableIfCommentsAreDisabled(): void
    {
        // arrange
        $this->configuration->boolean('comments.enabled')->willReturn(false);
        $request = $this->buildRequest();
        $response = $this->buildResponse();

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact('myArticleUuid'))
            ->shouldNotBeCalled();

        $this->commandBus
            ->dispatch(Argument::any())
            ->shouldNotBeCalled();

        $this->responseBuilder
            ->json([
                'error' => 'Posting new comments is currently disabled.',
            ], StatusCode::STATUS_SERVICE_UNAVAILABLE)
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->store($request);

        // assert
        $this->assertEquals($response, $result);
    }

    /** @test */
    public function storeReturnsErrorOnException(): void
    {
        // arrange
        $request = $this->buildRequest();
        $response = $this->buildResponse();

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact('myArticleUuid'))
            ->shouldBeCalled()
            ->willThrow(RecordNotFoundException::emptyResultSet());

        $this->commandBus
            ->dispatch(Argument::any())
            ->shouldNotBeCalled();

        $this->responseBuilder
            ->json([
                'error' => 'Comment could not be created.',
            ], StatusCode::STATUS_BAD_REQUEST)
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->store($request);

        // assert
        $this->assertEquals($response, $result);
    }

    /** @test */
    public function storeDispatchesCreateCommentCommandAndReturnsOk(): void
    {
        // arrange
        $request = $this->buildRequest();
        $response = $this->buildResponse();
        $article = $this->getArticle();

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact('myArticleUuid'))
            ->shouldBeCalled()
            ->willReturn($article);

        $this->commandBus
            ->dispatch(Argument::type(CreateComment::class))
            ->shouldBeCalled();

        $this->responseBuilder
            ->json([
                'success' => true,
            ], StatusCode::STATUS_OK)
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->store($request);

        // assert
        $this->assertEquals($response, $result);
    }

    /**
     * @return ObjectProphecy|CommentStoreRequestInterface
     */
    private function buildRequest()
    {
        /** @var ObjectProphecy|CommentStoreRequestInterface $request */
        $request = $this->prophesize(CommentStoreRequestInterface::class);

        $request->articleUuid()->willReturn('myArticleUuid');
        $request->content()->willReturn('myContent');
        $request->email()->willReturn('myEmail');
        $request->honeypot()->willReturn('');
        $request->ip()->willReturn('myIp');
        $request->name()->willReturn('myName');

        return $request->reveal();
    }

    protected function buildResponse(): ResponseInterface
    {
        return $this->getResponseFactory()
            ->createResponse(123)
            ->withBody(
                $this->getStreamFactory()->createStream('myResponseBody')
            );
    }
}
