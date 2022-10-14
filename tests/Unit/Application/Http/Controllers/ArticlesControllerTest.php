<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Http\Controllers\ArticlesController;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Http\StatusCode;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Tests\TestCase;

/**
 * @covers \App\Application\Http\Controllers\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    private ObjectProphecy|ArticleRepositoryInterface $articleRepository;

    private ObjectProphecy|ResponseBuilderInterface $responseBuilder;

    private ObjectProphecy|ArticlesController $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->articleRepository = $this->prophesize(ArticleRepositoryInterface::class);
        $this->responseBuilder = $this->prophesize(ResponseBuilderInterface::class);

        $this->controller = new ArticlesController(
            $this->articleRepository->reveal(),
            $this->responseBuilder->reveal()
        );
    }

    /** @test */
    public function indexReturnsIndexViewResponse(): void
    {
        // arrange
        $response = $this->buildResponse('ok', StatusCode::STATUS_OK);

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
        $uuid = 'asdasd';

        // arrange
        $request = $this->prophesize(ArticleShowRequestInterface::class);

        $request
            ->uuid()
            ->shouldBeCalled()
            ->willReturn($uuid);

        $request
            ->slug()
            ->shouldBeCalled()
            ->willReturn('requestSlug');

        $article = $this->getArticle([
            'slug' => 'articleSlug',
        ]);

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact($uuid))
            ->shouldBeCalled()
            ->willReturn($article);

        $response = $this->buildResponse('redirect', StatusCode::STATUS_MOVED_PERMANENTLY);

        $this->responseBuilder
            ->redirect(
                Argument::type('string'),
                Argument::type('array'),
                Argument::exact(StatusCode::STATUS_MOVED_PERMANENTLY)
            )
            ->shouldBeCalled()
            ->willReturn($response);

        // act
        $result = $this->controller->show($request->reveal());

        $this->assertEquals($response, $result);
    }

    /** @test */
    public function showReturnsViewResponse(): void
    {
        $slug = 'mySlugValue';
        $uuid = 'asdasd';

        // arrange
        $request = $this->prophesize(ArticleShowRequestInterface::class);

        $request
            ->uuid()
            ->shouldBeCalled()
            ->willReturn($uuid);

        $request
            ->slug()
            ->shouldBeCalled()
            ->willReturn($slug);

        $article = $this->getArticle([
            'slug' => $slug,
        ]);

        $this->articleRepository
            ->getPublishedByUuid(Argument::exact($uuid))
            ->shouldBeCalled()
            ->willReturn($article);

        $response = $this->buildResponse('ok', StatusCode::STATUS_OK);

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
