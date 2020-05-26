<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Core;

use App\Application\Core\ResponseBuilder;
use App\Application\Http\StatusCode;
use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Interfaces\ViewBuilderInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tests\TestCase;

/**
 * @covers \App\Application\Core\ResponseBuilder
 */
class ResponseBuilderTest extends TestCase
{
    /** @var ObjectProphecy|ViewBuilderInterface */
    private $viewBuilder;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    /** @var ObjectProphecy|UrlGeneratorInterface */
    private $urlGenerator;

    /** @var ServerRequestInterface */
    private $request;

    /** @var ObjectProphecy|SessionInterface */
    private $session;

    /** @var ResponseBuilder */
    private $responseBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->viewBuilder = $this->prophesize(ViewBuilderInterface::class);
        $this->responseFactory = new Psr17Factory();
        $this->streamFactory = new Psr17Factory();
        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->request = new ServerRequest('GET', '/');
        $this->session = $this->prophesize(SessionInterface::class);

        $this->responseBuilder = new ResponseBuilder(
            $this->viewBuilder->reveal(),
            $this->responseFactory,
            $this->streamFactory,
            $this->urlGenerator->reveal(),
            $this->request,
            $this->session->reveal()
        );
    }

    /** @test */
    public function itReturnsOkResponse(): void
    {
        // arrange
        $this->viewBuilder->render(Argument::type('string'), Argument::type('array'))
            ->willReturn('myViewString');

        // act
        $response = $this->responseBuilder->ok('foo.view', ['id' => 123]);

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('myViewString', (string) $response->getBody());
    }

    /** @test */
    public function itReturnsRedirectResponse(): void
    {
        // arrange
        $this->urlGenerator->route(Argument::type('string'), Argument::type('array'))
            ->willReturn('fooUrl');

        // act
        $response = $this->responseBuilder->redirect('articles.index');

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_FOUND, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('fooUrl', $headers['Location'][0]);
    }

    /** @test */
    public function itRedirectsBackToTheReferer(): void
    {
        // arrange
        $request = $this->request->withHeader('referer', 'http://referer.url');
        $this->session->previousUrl()->willReturn('http://session.url');
        $this->urlGenerator->route(Argument::any())->willReturn('http://fallback.url');

        $this->responseBuilder = new ResponseBuilder(
            $this->viewBuilder->reveal(),
            $this->responseFactory,
            $this->streamFactory,
            $this->urlGenerator->reveal(),
            $request,
            $this->session->reveal()
        );

        // act
        $response = $this->responseBuilder->redirectBack();

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_FOUND, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('http://referer.url', $headers['Location'][0]);
    }

    /** @test */
    public function itRedirectsBackToThePreviousUrl(): void
    {
        // arrange
        // no referer
        $this->session->previousUrl()->willReturn('http://session.url');
        $this->urlGenerator->route(Argument::any())->willReturn('http://fallback.url');

        // act
        $response = $this->responseBuilder->redirectBack(StatusCode::STATUS_SEE_OTHER);

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_SEE_OTHER, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('http://session.url', $headers['Location'][0]);
    }

    /** @test */
    public function itRedirectsBackToTheFallbackUrl(): void
    {
        // arrange
        // no referer
        // no previous url in session
        $this->urlGenerator->route(Argument::any())->willReturn('http://fallback.url');

        // act
        $response = $this->responseBuilder->redirectBack();

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_FOUND, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('http://fallback.url', $headers['Location'][0]);
    }

    /** @test */
    public function itRedirectsBackWithoutErrors(): void
    {
        // arrange
        $request = $this->request->withHeader('referer', 'http://referer.url');

        $this->session->flashErrors(Argument::any())
            ->shouldNotBeCalled();

        $this->responseBuilder = new ResponseBuilder(
            $this->viewBuilder->reveal(),
            $this->responseFactory,
            $this->streamFactory,
            $this->urlGenerator->reveal(),
            $request,
            $this->session->reveal()
        );

        // act
        $this->responseBuilder->redirectBack();
    }

    /** @test */
    public function itRedirectsBackWithErrors(): void
    {
        // arrange
        $request = $this->request->withHeader('referer', 'http://referer.url');

        $errors = [
            'key' => 'value',
        ];

        $this->session->flashErrors($errors)
            ->shouldBeCalled();

        $this->responseBuilder = new ResponseBuilder(
            $this->viewBuilder->reveal(),
            $this->responseFactory,
            $this->streamFactory,
            $this->urlGenerator->reveal(),
            $request,
            $this->session->reveal()
        );

        // act
        $this->responseBuilder->redirectBack(
            StatusCode::STATUS_FOUND,
            $errors
        );
    }

    /** @test */
    public function itRedirectsToIntendedUrl(): void
    {
        // arrange
        $this->session->intendedUrl()->willReturn('http://intended.url');
        $this->urlGenerator->route(Argument::any())->willReturn('http://fallback.url');

        // act
        $response = $this->responseBuilder->redirectIntended('any', StatusCode::STATUS_SEE_OTHER);

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_SEE_OTHER, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('http://intended.url', $headers['Location'][0]);
    }

    /** @test */
    public function itRedirectsToFallbackUrl(): void
    {
        // arrange
        // no intended url
        $this->urlGenerator->route(Argument::any())->willReturn('http://fallback.url');

        // act
        $response = $this->responseBuilder->redirectIntended('any');

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_FOUND, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('http://fallback.url', $headers['Location'][0]);
    }

    /** @test */
    public function itReturnsXmlResponse(): void
    {
        // arrange
        $this->viewBuilder->render(Argument::type('string'))
            ->willReturn('myViewString');

        // act
        $response = $this->responseBuilder->xml('articles.index');

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(StatusCode::STATUS_OK, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('text/xml', $headers['Content-Type'][0]);
    }
}
